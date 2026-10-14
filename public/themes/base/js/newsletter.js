document.addEventListener('alpine:init', () => {

    Alpine.data('newsletterPopup', () => ({

        modal: null,

        init() {
            this.modal = new bootstrap.Modal(
                document.getElementById('newsletterPopup')
            );

            fetch('/newsletter/status', {
                credentials: 'same-origin'
            })
                .then(r => r.json())
                .then(({ can_open }) => {
                    if (can_open) {
                        //this.modal.show();
                    }
                });
        },

        submit() {
            const email = this.$refs.email.value;

            fetch('/newsletter/subscribe', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ email })
            }).then(() => {
                this.modal.hide();
            });
        },

        close() {
            fetch('/newsletter/seen', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(() => {
                this.modal.hide();
            });
        }

    }));
});
