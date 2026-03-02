<!-- WhatsApp Modal -->
<div id="whatsapp-modal" class="fixed inset-0 z-50 hidden">
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>
  
  <!-- Modal Content -->
  <div class="relative inset-0 flex items-center justify-center p-4">
    <div class="relative w-full max-w-[400px] bg-white dark:bg-surface-dark rounded-xl shadow-2xl animate-in fade-in zoom-in duration-300">
      
      <!-- Close Button -->
      <button id="close-whatsapp-modal" class="absolute right-4 top-4 p-2 rounded-full text-gray-400 hover:text-primary hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors z-10">
        <span class="material-symbols-outlined">close</span>
      </button>
      
      <!-- Modal Header -->
      <div class="pt-6 px-6 pb-2 text-center">
        <div class="mx-auto w-10 h-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mb-3">
          <!-- WhatsApp Icon -->
          <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.304-5.235c0-5.443 4.429-9.873 9.876-9.873 2.639 0 5.12 1.03 6.988 2.898a9.825 9.825 0 012.893 6.991c-.003 5.444-4.432 9.874-9.877 9.874"/>
          </svg>
        </div>
        <h3 class="text-xl font-bold text-foreground-light dark:text-foreground-dark">Chat via WhatsApp</h3>
        <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">
          Quick assistance for your inquiries
        </p>
      </div>
      
      <!-- Form Content -->
      <div class="px-6 pb-6">
        <form id="whatsapp-form" class="flex flex-col gap-3">
          <!-- Name Field -->
          <div class="relative">
            <input 
              class="w-full h-10 rounded border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-black/20 px-3 pr-10 text-sm text-foreground-light dark:text-foreground-dark focus:border-primary focus:ring-1 focus:ring-primary placeholder:text-gray-400 dark:placeholder:text-gray-600 transition-all outline-none" 
              id="wa-name" 
              placeholder="Your Name *" 
              type="text"
              required
            />
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none">person</span>
          </div>
          
          <!-- Email Field -->
          <div class="relative">
            <input 
              class="w-full h-10 rounded border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-black/20 px-3 pr-10 text-sm text-foreground-light dark:text-foreground-dark focus:border-primary focus:ring-1 focus:ring-primary placeholder:text-gray-400 dark:placeholder:text-gray-600 transition-all outline-none" 
              id="wa-email" 
              placeholder="Email (Optional)" 
              type="email"
            />
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none">mail</span>
          </div>
          
          <!-- Question Field -->
          <div class="relative">
            <textarea 
              class="w-full h-20 rounded border border-gray-300 dark:border-gray-600 bg-background-light dark:bg-black/20 p-3 text-sm text-foreground-light dark:text-foreground-dark focus:border-primary focus:ring-1 focus:ring-primary placeholder:text-gray-400 dark:placeholder:text-gray-600 transition-all outline-none resize-none" 
              id="wa-question" 
              placeholder="Your Message *"
              required
            ></textarea>
          </div>
          
          <!-- Simple Captcha -->
          <div class="flex items-center gap-2">
            <span class="text-xs text-gray-600 dark:text-gray-400">Verify: <span class="font-bold text-primary" id="captcha-question">4 + 3</span> =</span>
            <input 
              class="w-16 h-8 rounded text-center text-sm font-bold border border-gray-300 dark:border-gray-600 bg-white dark:bg-black/40 focus:border-primary focus:ring-1 focus:ring-primary outline-none" 
              id="captcha-answer" 
              placeholder="?" 
              type="number"
              required
            />
          </div>
          
          <!-- Checkbox Consent -->
          <label class="flex items-center gap-2 cursor-pointer text-xs">
            <input 
              class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-black/20 text-primary focus:ring-primary" 
              id="wa-consent" 
              type="checkbox"
              required
            />
            <span class="text-gray-500 dark:text-gray-400">
              I agree to be contacted via WhatsApp
            </span>
          </label>
          
          <!-- Submit Button -->
          <button 
            class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-green-500 to-green-600 text-white text-sm font-medium hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg active:scale-[0.98]" 
            type="submit"
          >
            <i class="fab fa-whatsapp text-lg" style="color: white"></i>
            <span>Send Message</span>
          </button>
        </form>
      </div>
      
      <!-- Footer Decorative Stripe -->
      <div class="h-1.5 w-full bg-gradient-to-r from-primary via-pink-400 to-primary"></div>
    </div>
  </div>
</div>

<!-- Custom Transparent Scrollbar Styles -->
<style>
  .custom-scrollbar::-webkit-scrollbar {
    width: 6px;
  }
  .custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
  }
  .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.3);
    border-radius: 20px;
    border: 1px solid transparent;
    background-clip: padding-box;
  }
  .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(156, 163, 175, 0.5);
  }
  .dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(107, 114, 128, 0.3);
    background-clip: padding-box;
  }
  .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(107, 114, 128, 0.5);
  }
  
  /* Make scrollbar completely transparent for better visual effect */
  .custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: rgba(156, 163, 175, 0.3) transparent;
    -webkit-overflow-scrolling: touch;
  }
  
  /* Ensure modal content is scrollable on mobile */
  @media (max-height: 700px) {
    .custom-scrollbar {
      max-height: 60vh !important;
    }
  }
  
  @keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  @keyframes zoom-in {
    from { 
      opacity: 0;
      transform: scale(0.95);
    }
    to { 
      opacity: 1;
      transform: scale(1);
    }
  }
  
  .animate-in {
    animation-duration: 0.3s;
    animation-timing-function: ease-out;
    animation-fill-mode: both;
  }
  
  .fade-in {
    animation-name: fade-in;
  }
  
  .zoom-in {
    animation-name: zoom-in;
  }
</style>
