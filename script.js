
        function toggleFields() {
            const role = document.querySelector('select[name="peranan"]').value;
            document.getElementById('vendor-fields').style.display = role === 'vendor' ? 'block' : 'none';
            document.getElementById('admin-field').style.display = role === 'admin' ? 'block' : 'none';
            document.getElementById('default-terms').style.display = (role === 'pengguna') ? 'block' : 'none';
            document.getElementById('sekolah/agensi-terms').style.display = (role === 'sekolah/agensi') ? 'block' : 'none';
            document.getElementById('vendor-terms').style.display = (role === 'vendor') ? 'block' : 'none';
            document.getElementById('terms-label').style.display = (role !== 'admin') ? 'block' : 'none';

            // Papar ID auto dijana
            const idField = document.getElementById('generated-id');
            const prefixes = {
                'pengguna': 'PE',
                'sekolah/agensi': 'SA',
                'vendor': 'VE',
                'admin': 'AD'
            };
            if (role && prefixes[role]) {
                // Sementara, paparkan ID dummy hingga server hasilkan sebenar
                idField.value = prefixes[role] + '???';
                idField.parentElement.style.display = 'block';
            } else {
                idField.parentElement.style.display = 'none';
            }
        }

        // Fungsi toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById("passwordField");
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
        }
 