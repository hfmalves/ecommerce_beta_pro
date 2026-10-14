<?php

namespace App\Controllers\Admin\Website;

use App\Controllers\BaseController;
use App\Models\Website\HomeModel;
use App\Models\Website\HomeBlockModel;
class ShowcasesController extends BaseController
{
    protected $HomeModel;
    protected $HomeBlockModel;

    public function __construct()
    {
        $this->HomeModel = new HomeModel();
        $this->HomeBlockModel = new HomeBlockModel();
    }
    public function index()
    {
        $showcases = $this->HomeModel->findAll();
        return view('admin/website/showcases/index', [
            'showcases' => $showcases,
        ]);
    }
    public function create()
    {
        $data = $this->request->getJSON(true);
        $isDefault = !empty($data['is_default']) ? 1 : 0;
        if ($isDefault === 1) {
            $this->HomeModel
                ->where('store_id', 1)
                ->set(['is_default' => 0])
                ->update();
        }
        $insert = [
            'store_id'     => 1,
            'home_code' => 'home-' . (
                    ($this->HomeModel
                        ->where('store_id', 1)
                        ->withDeleted()
                        ->countAllResults()) + 1
                ),
            'name'         => $data['name'] ?? null,
            'is_active'    => !empty($data['is_active']) ? 1 : 0,
            'is_default'   => $isDefault,
            'active_start' => $data['active_start'] ?: null,
            'active_end'   => $data['active_end'] ?: null,
        ];
        $this->HomeModel->insert($insert);
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Home criada com sucesso'
        ]);
    }
    public function edit($id)
    {
        $blocks = $this->HomeBlockModel->where('home_id', $id)->orderBy('position', 'asc')->findAll();
        $showcase = $this->HomeModel->find($id);
        if (!$showcase) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $themeImages = [];

        foreach ($blocks as $block) {
            $file = FCPATH . 'theme/theme_' . $block['block_type'] . '.png';

            if (is_file($file)) {
                $themeImages[$block['id']] = [
                    base_url('theme/theme_' . $block['block_type'] . '.png')
                ];
            } else {
                $themeImages[$block['id']] = [];
            }
        }

        $blockChoices = config('ShowcaseBlocks')->types;
        foreach ($blockChoices as $type => &$choice) {
            $image = 'theme/theme_' . $type . '.png';
            $choice['images'] = is_file(FCPATH . $image) ? [base_url($image)] : [];
        }
        unset($choice);
        $settingsForm = [
            'id' => (int) $showcase['id'], 'name' => $showcase['name'] ?? '',
            'is_active' => (int) $showcase['is_active'], 'is_default' => (int) $showcase['is_default'],
            'active_start' => str_replace(' ', 'T', $showcase['active_start'] ?? ''),
            'active_end' => str_replace(' ', 'T', $showcase['active_end'] ?? ''),
            csrf_token() => csrf_hash(),
        ];
        return view('admin/website/showcases/edit', [
            'showcase' => $showcase,
            'blocks'   => $blocks,
            'images'  => $themeImages,
            'blockChoices' => $blockChoices,
            'settingsJson' => json_encode($settingsForm, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
        ]);
    }
    public function update()
    {
        $data = $this->request->getJSON(true);

        $id = $data['id'] ?? null;
        if (! $id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID em falta',
                'csrf' => [
                    'token' => csrf_token(),
                    'hash'  => csrf_hash(),
                ],
            ]);
        }

        // regra: default único
        if (!empty($data['is_default'])) {
            $this->HomeModel
                ->where('store_id', 1)
                ->where('id !=', $id)
                ->set(['is_default' => 0])
                ->update();
        }

        $update = [
            'name'         => $data['name'] ?? null,
            'is_active'    => !empty($data['is_active']) ? 1 : 0,
            'is_default'   => !empty($data['is_default']) ? 1 : 0,
            'active_start' => $data['active_start'] ?: null,
            'active_end'   => $data['active_end'] ?: null,
        ];

        if (! $this->HomeModel->update($id, $update)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->HomeModel->errors(),
                'csrf'   => [
                    'token' => csrf_token(),
                    'hash'  => csrf_hash(),
                ],
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Home atualizada com sucesso!',
            'csrf'    => [
                'token' => csrf_token(),
                'hash'  => csrf_hash(),
            ],
        ]);
    }

    public function reorderBlocks()
    {
        $data = $this->request->getJSON(true);
        $reply = function ($status, $message, $code = 200) {
            return $this->response->setStatusCode($code)->setJSON([
                'status' => $status, 'message' => $message,
                'csrf' => ['token' => csrf_token(), 'hash' => csrf_hash()],
            ]);
        };
        $homeId = is_array($data) ? filter_var($data['home_id'] ?? null, FILTER_VALIDATE_INT) : false;
        $ids = $data['block_ids'] ?? null;
        if (! $homeId || $homeId < 1 || ! is_array($ids) || ! array_is_list($ids)) {
            return $reply('error', 'Ordem inválida.', 422);
        }
        foreach ($ids as $id) {
            if (! filter_var($id, FILTER_VALIDATE_INT) || (int) $id < 1) {
                return $reply('error', 'Bloco inválido.', 422);
            }
        }
        $ids = array_map('intval', $ids);
        if (count($ids) !== count(array_unique($ids))) {
            return $reply('error', 'Existem blocos repetidos.', 422);
        }
        if (! $this->HomeModel->find($homeId)) {
            return $reply('error', 'Vitrine não encontrada.', 404);
        }
        $db = db_connect();
        $db->transBegin();
        try {
            $table = $db->escapeIdentifiers($db->prefixTable('website_home_blocks'));
            $rows = $db->query("SELECT id FROM {$table} WHERE home_id = ? FOR UPDATE", [$homeId])->getResultArray();
            $expected = array_map('intval', array_column($rows, 'id'));
            $received = $ids;
            sort($expected);
            sort($received);
            if ($expected !== $received) {
                $db->transRollback();
                return $reply('error', 'Os blocos mudaram. Recarregue a página.', 409);
            }
            foreach ($ids as $position => $id) {
                if (! $db->table('website_home_blocks')->where('home_id', $homeId)->where('id', $id)
                    ->update(['position' => $position + 1])) {
                    throw new \RuntimeException('Update failed');
                }
            }
            if (! $db->transStatus() || ! $db->transCommit()) {
                throw new \RuntimeException('Commit failed');
            }
        } catch (\Throwable $error) {
            $db->transRollback();
            log_message('error', 'Failed to reorder showcase blocks: {message}', ['message' => $error->getMessage()]);
            return $reply('error', 'Não foi possível guardar a ordem.', 500);
        }
        return $reply('success', 'Ordem guardada.');
    }

    public function addBlock()
    {
        $data = $this->request->getJSON(true);
        $homeId = filter_var($data['home_id'] ?? null, FILTER_VALIDATE_INT);
        $types = array_keys(config('ShowcaseBlocks')->types);
        $type = $data['block_type'] ?? '';
        if (! $homeId || ! is_string($type) || ! in_array($type, $types, true)
            || ! $this->HomeModel->find($homeId)) {
            return $this->blockReply('error', 'Selecione uma vitrine e um tipo de bloco válido.', 422);
        }
        $db = db_connect();
        $db->transBegin();
        try {
            $homeTable = $db->escapeIdentifiers($db->prefixTable('website_homes'));
            $db->query("SELECT id FROM {$homeTable} WHERE id = ? FOR UPDATE", [$homeId]);
            $last = $db->table('website_home_blocks')->selectMax('position', 'last_position')->where('home_id', $homeId)->get()->getRowArray();
            $settings = ['config' => [], 'items' => []];
            if ($type === 'hero') { $settings['slides'] = []; }
            if ($type === 'top_category_filter') { $settings['tabs'] = []; }
            if ($type === 'footer') { $settings['categories'] = []; }
            if ($type === 'faq') { $settings['faq_id'] = null; }
            if (! $db->table('website_home_blocks')->insert([
                'home_id' => $homeId, 'block_type' => $type,
                'position' => (int) ($last['last_position'] ?? 0) + 1, 'is_active' => 0,
                'settings' => json_encode($settings, JSON_THROW_ON_ERROR), 'settings_version' => 1,
                'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'),
            ])) { throw new \RuntimeException('Insert failed'); }
            $newBlockId = (int) $db->insertID();
            $block = $db->table('website_home_blocks')->where('id', $newBlockId)->get()->getRowArray();
            $imagePath = 'theme/theme_' . $type . '.png';
            $blockHtml = view('admin/website/showcases/block_card', [
                'block' => $block,
                'blockImages' => is_file(FCPATH . $imagePath) ? [base_url($imagePath)] : [],
            ]);
            if (! $db->transStatus() || ! $db->transCommit()) { throw new \RuntimeException('Commit failed'); }
        } catch (\Throwable $error) {
            $db->transRollback();
            log_message('error', 'Add showcase block failed: {message}', ['message' => $error->getMessage()]);
            return $this->blockReply('error', 'Não foi possível adicionar o bloco.', 500);
        }
        return $this->blockReply('success', 'Bloco adicionado como inativo.', 200, [
            'block_id' => $newBlockId, 'block_html' => $blockHtml,
        ]);
    }

    public function deleteBlock()
    {
        $data = $this->request->getJSON(true);
        $homeId = filter_var($data['home_id'] ?? null, FILTER_VALIDATE_INT);
        $blockId = filter_var($data['block_id'] ?? null, FILTER_VALIDATE_INT);
        if (! $homeId || ! $blockId || ! $this->HomeModel->find($homeId)) {
            return $this->blockReply('error', 'Bloco ou vitrine inválidos.', 422);
        }
        $db = db_connect();
        $db->transBegin();
        try {
            $table = $db->escapeIdentifiers($db->prefixTable('website_home_blocks'));
            $rows = $db->query("SELECT id FROM {$table} WHERE home_id = ? ORDER BY position, id FOR UPDATE", [$homeId])->getResultArray();
            if (! in_array($blockId, array_map('intval', array_column($rows, 'id')), true)) {
                $db->transRollback();
                return $this->blockReply('error', 'Bloco não encontrado nesta vitrine.', 404);
            }
            if (! $db->table('website_home_blocks')->where('home_id', $homeId)->where('id', $blockId)->delete()) {
                throw new \RuntimeException('Delete failed');
            }
            $position = 1;
            foreach ($rows as $row) {
                if ((int) $row['id'] !== $blockId) {
                    if (! $db->table('website_home_blocks')->where('home_id', $homeId)->where('id', $row['id'])->update(['position' => $position++])) {
                        throw new \RuntimeException('Reorder failed');
                    }
                }
            }
            if (! $db->transStatus() || ! $db->transCommit()) { throw new \RuntimeException('Commit failed'); }
        } catch (\Throwable $error) {
            $db->transRollback();
            log_message('error', 'Delete showcase block failed: {message}', ['message' => $error->getMessage()]);
            return $this->blockReply('error', 'Não foi possível eliminar o bloco.', 500);
        }
        return $this->blockReply('success', 'Bloco eliminado.');
    }

    private function blockReply(string $status, string $message, int $code = 200, array $extra = [])
    {
        return $this->response->setStatusCode($code)->setJSON($extra + [
            'status' => $status, 'message' => $message,
            'csrf' => ['token' => csrf_token(), 'hash' => csrf_hash()],
        ]);
    }

    public function deactivate()
    {
        $data = $this->request->getJSON(true);
        if (empty($data['id'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID inválido'
            ]);
        }
        $this->HomeModel->update($data['id'], [
            'is_active' => 0,
        ]);
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Home desativada com sucesso'
        ]);
    }
    public function delete()
    {
        $data = $this->request->getJSON(true);
        if (empty($data['id'])) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID inválido'
            ]);
        }
        $this->HomeModel->delete($data['id']);
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Home eliminada com sucesso'
        ]);
    }
}
