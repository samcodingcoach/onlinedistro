document.addEventListener('DOMContentLoaded', function() {
    const whatsappModal = document.getElementById('whatsapp-modal');
    const whatsappFloatBtn = document.getElementById('whatsapp-float-btn');
    const closeWhatsAppModal = document.getElementById('close-whatsapp-modal');
    const whatsappForm = document.getElementById('whatsapp-form');
    const captchaQuestion = document.getElementById('captcha-question');
    
    // Generate random simple math captcha
    function generateCaptcha() {
        const num1 = Math.floor(Math.random() * 10) + 1;
        const num2 = Math.floor(Math.random() * 10) + 1;
        captchaQuestion.textContent = `${num1} + ${num2}`;
        captchaQuestion.dataset.answer = num1 + num2;
    }
    
    // Generate captcha on load
    generateCaptcha();
    
    // Open modal when floating button is clicked
    whatsappFloatBtn.addEventListener('click', function(e) {
        e.preventDefault();
        whatsappModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });
    
    // Close modal when close button is clicked
    closeWhatsAppModal.addEventListener('click', function() {
        whatsappModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    });
    
    // Close modal when clicking on backdrop
    whatsappModal.addEventListener('click', function(e) {
        if (e.target === whatsappModal || e.target.classList.contains('backdrop-blur-sm')) {
            whatsappModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Function to show error message
    function showError(inputElement, message) {
        // Remove existing error
        removeError(inputElement);
        
        // Add error styling
        inputElement.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
        
        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-xs mt-1 flex items-center gap-1';
        errorDiv.innerHTML = `
            <span class="material-symbols-outlined text-xs">error</span>
            <span>${message}</span>
        `;
        
        // Insert error message after input
        inputElement.parentNode.insertBefore(errorDiv, inputElement.nextSibling);
    }
    
    // Function to remove error message
    function removeError(inputElement) {
        inputElement.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
        const errorDiv = inputElement.parentNode.querySelector('.text-red-500');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    // Function to show checkbox error
    function showCheckboxError() {
        const consentLabel = document.getElementById('wa-consent').parentNode;
        
        // Remove existing error
        const existingError = consentLabel.querySelector('.text-red-500');
        if (existingError) {
            existingError.remove();
        }
        
        // Add error styling
        document.getElementById('wa-consent').classList.add('border-red-500');
        
        // Create error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-xs mt-1 flex items-center gap-1';
        errorDiv.innerHTML = `
            <span class="material-symbols-outlined text-xs">error</span>
            <span>Please agree to be contacted via WhatsApp</span>
        `;
        
        // Insert error message after checkbox
        consentLabel.parentNode.insertBefore(errorDiv, consentLabel.nextSibling);
    }
    
    // Function to remove checkbox error
    function removeCheckboxError() {
        document.getElementById('wa-consent').classList.remove('border-red-500');
        const consentLabel = document.getElementById('wa-consent').parentNode;
        const errorDiv = consentLabel.parentNode.querySelector('.text-red-500');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    // Handle form submission
    whatsappForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear all previous errors
        document.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
        });
        document.querySelectorAll('.text-red-500').forEach(el => el.remove());
        
        const name = document.getElementById('wa-name').value.trim();
        const email = document.getElementById('wa-email').value.trim();
        const question = document.getElementById('wa-question').value.trim();
        const captchaAnswer = document.getElementById('captcha-answer').value.trim();
        const consent = document.getElementById('wa-consent').checked;
        const expectedAnswer = captchaQuestion.dataset.answer;
        
        let hasError = false;
        
        // Validate name
        if (!name) {
            showError(document.getElementById('wa-name'), 'Name is required');
            hasError = true;
        }
        
        // Validate email if provided
        if (email && !isValidEmail(email)) {
            showError(document.getElementById('wa-email'), 'Please enter a valid email address');
            hasError = true;
        }
        
        // Validate question
        if (!question) {
            showError(document.getElementById('wa-question'), 'Message is required');
            hasError = true;
        } else if (question.length < 10) {
            showError(document.getElementById('wa-question'), 'Message must be at least 10 characters');
            hasError = true;
        }
        
        // Validate captcha
        if (!captchaAnswer) {
            showError(document.getElementById('captcha-answer'), 'Please answer the captcha');
            hasError = true;
        } else if (parseInt(captchaAnswer) !== parseInt(expectedAnswer)) {
            showError(document.getElementById('captcha-answer'), 'Incorrect answer, please try again');
            generateCaptcha();
            document.getElementById('captcha-answer').value = '';
            hasError = true;
        }
        
        // Validate consent
        if (!consent) {
            showCheckboxError();
            hasError = true;
        }
        
        if (hasError) {
            return;
        }
        
        // Check if there are items in the cart
        const cartItems = window.cartItems || [];
        const phoneNumber = window.distroPhone || '628123456789';
        let message;
        
        if (cartItems.length > 0) {
            // Create order message with cart items
            message = `Halo, saya ingin memesan produk berikut:\n\n`;
            message += `Nama: ${name}\n`;
            if (email) {
                message += `Email: ${email}\n`;
            }
            message += `\n`;
            
            let total = 0;
            cartItems.forEach((item, index) => {
                const subtotal = item.harga_aktif * item.qty;
                total += subtotal;
                message += `${index + 1}. ${item.nama_produk}\n`;
                message += `   Kode: ${item.kode_produk}\n`;
                message += `   Harga: Rp ${item.harga_aktif.toLocaleString('id-ID')} x ${item.qty}\n`;
                message += `   Subtotal: Rp ${subtotal.toLocaleString('id-ID')}\n\n`;
            });
            
            message += `Total: Rp ${total.toLocaleString('id-ID')}\n\n`;
            message += `Catatan tambahan: ${question}\n\n`;
            message += 'Mohon informasikan ketersediaan dan cara pemesanannya. Terima kasih!';
        } else {
            // Create simple inquiry message
            message = `Halo APRIL Fashion,\n\n`;
            message += `Nama: ${name}\n`;
            if (email) {
                message += `Email: ${email}\n`;
            }
            message += `Pertanyaan: ${question}\n\n`;
            message += `Dikirim dari website APRIL`;
        }
        
        // Create WhatsApp URL
        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;
        
        // Open WhatsApp in new tab
        window.open(whatsappUrl, '_blank');
        
        // Close modal and reset form
        whatsappModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        whatsappForm.reset();
        generateCaptcha();
    });
    
    // Email validation helper function
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Add real-time validation listeners
    document.getElementById('wa-name').addEventListener('blur', function() {
        if (this.value.trim()) {
            removeError(this);
        }
    });
    
    document.getElementById('wa-email').addEventListener('blur', function() {
        const email = this.value.trim();
        if (!email || isValidEmail(email)) {
            removeError(this);
        }
    });
    
    document.getElementById('wa-question').addEventListener('blur', function() {
        if (this.value.trim() && this.value.trim().length >= 10) {
            removeError(this);
        }
    });
    
    document.getElementById('captcha-answer').addEventListener('blur', function() {
        if (this.value.trim() && parseInt(this.value.trim()) === parseInt(captchaQuestion.dataset.answer)) {
            removeError(this);
        }
    });
    
    document.getElementById('wa-consent').addEventListener('change', function() {
        if (this.checked) {
            removeCheckboxError();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !whatsappModal.classList.contains('hidden')) {
            whatsappModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    });
});
