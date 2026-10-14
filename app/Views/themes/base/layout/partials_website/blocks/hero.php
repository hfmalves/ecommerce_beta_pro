<?php
$slides = $block['data']['slides'] ?? [];
if (!$slides) return;
?>

<?php if (!empty($slides)): ?>
<section class="swiper-container js-swiper-slider slideshow full-width_padding-20 slideshow-md"
         data-settings='{
        "autoplay": {
          "delay": 5000
        },
        "slidesPerView": 1,
        "effect": "fade",
        "loop": true,
        "pagination": {
          "el": ".slideshow-pagination",
          "type": "bullets",
          "clickable": true
        }
      }'>
    <div class="swiper-wrapper">
        <?php foreach ($slides as $slide): ?>
            <div class="swiper-slide">
                <div class="overflow-hidden position-relative h-100">
                    <div class="slideshow-bg">
                        <img
                                src="<?= base_url('uploads/hero/' . esc($slide['background_image'])) ?>"
                                onerror="this.onerror=null;this.src='https://placehold.co/1863x700?text=<?= rawurlencode($slide['title'] ?? 'Hero') ?>';"
                                alt="<?= esc($slide['title']) ?>"
                                class="slideshow-bg__img object-fit-cover object-position-right"
                        />
                    </div>
                    <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                        <h2 class="text-uppercase h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">
                            <?= esc($slide['title']) ?>
                        </h2>
                        <?php if (!empty($slide['description'])): ?>
                            <p class="animate animate_fade animate_btt animate_delay-6">
                                <?= esc($slide['description']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($slide['button_text']) && !empty($slide['button_link'])): ?>
                            <a
                                    href="<?= esc($slide['button_link']) ?>"
                                    class="btn-link btn-link_sm default-underline text-uppercase fw-medium animate animate_fade animate_btt animate_delay-7"
                            >
                                <?= esc($slide['button_text']) ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="slideshow-pagination position-left-center"></div>
</section>
    <?php endif; ?>