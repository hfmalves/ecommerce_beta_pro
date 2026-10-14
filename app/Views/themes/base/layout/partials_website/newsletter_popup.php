<?php if (!empty($newsletter)): ?>
    <div
            class="modal fade"
            id="newsletterPopup"
            tabindex="-1"
            aria-hidden="true"
            x-data="newsletterPopup"
    >
        <div class="modal-dialog modal-dialog-centered newsletter-popup">
            <div class="modal-content">

                <button
                        type="button"
                        class="btn-close"
                        @click="close"
                ></button>

                <div class="row p-0 m-0">

                    <?php if (!empty($newsletter['image'])): ?>
                        <div class="col-md-6 p-0 d-none d-md-block">
                            <img
                                    src="<?= esc($newsletter['image']) ?>"
                                    onerror="this.onerror=null;this.src='https://placehold.co/600x400';"
                                    class="h-100 w-100 object-fit-cover"
                                    alt=""
                            >
                        </div>
                    <?php endif; ?>

                    <div class="col-md-6 d-flex align-items-center">
                        <div class="block-newsletter w-100">

                            <h3><?= esc($newsletter['title']) ?></h3>
                            <p><?= esc($newsletter['description']) ?></p>

                            <form
                                    class="footer-newsletter__form position-relative bg-body"
                                    @submit.prevent="submit"
                            >
                                <input
                                        class="form-control border-2"
                                        type="email"
                                        placeholder="Endereço de email"
                                        x-ref="email"
                                        required
                                >

                                <input
                                        class="btn-link fw-medium bg-transparent position-absolute top-0 end-0 h-100"
                                        type="submit"
                                        value="Entrar"
                                >
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
