window.ShowcaseAdmin = window.ShowcaseAdmin || {};
window.ShowcaseAdmin.request = () => ({
        async request(url, fields) {
            const response = await fetch(url, {
                method: 'POST', credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ home_id: Number(this.$root.dataset.homeId), ...fields,
                    [this.$root.dataset.csrfName]: this.$root.dataset.csrfHash })
            });
            const result = await response.json();
            if (result.csrf) {
                this.$root.dataset.csrfName = result.csrf.token;
                this.$root.dataset.csrfHash = result.csrf.hash;
            }
            if (!response.ok || result.status !== 'success') throw new Error(result.message || 'Falha de comunicação.');
            return result;
        },

});
