<!--Footer-->
<footer class="bg-gradient-to-r from-[#004689] to-[#002F5C] text-white py-16 mt-auto">
  <div class="max-w-[95%] mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
    <div class="flex flex-col justify-start">
      <a href="{{ route('home') }}" class="font-extrabold text-3xl flex items-center gap-3 tracking-wide hover:opacity-90 transition">
        <span class="text-4xl">
          <svg class="w-12 h-12">
            <use xlink:href="{{ asset('assets/sprite.svg#icon-main') }}" />
          </svg>
        </span>
        SYNAPSE
      </a>
    </div>

    <div>
      <h4 class="font-bold text-lg md:text-xl mb-6">Sobre nosotros</h4>
      <ul class="space-y-4 text-sm md:text-base text-gray-200 font-light">
        <li><a href="#" class="hover:text-white hover:underline transition">Acerca de Synapse</a></li>
        <li><a href="#" class="hover:text-white hover:underline transition">Atención al cliente</a></li>
        <li><a href="#" class="hover:text-white hover:underline transition">Preguntas frecuentes</a></li>
      </ul>
    </div>

    <div>
      <h4 class="font-bold text-lg md:text-xl mb-6">Términos y condiciones</h4>
      <ul class="space-y-4 text-sm md:text-base text-gray-200 font-light">
        <li><a href="#" class="hover:text-white hover:underline transition">Información</a></li>
        <li><a href="#" class="hover:text-white hover:underline transition">Política de privacidad</a></li>
      </ul>
    </div>

    <div>
      <h4 class="font-bold text-lg md:text-xl mb-6">Trabaja con nosotros</h4>
      <ul class="space-y-4 text-sm md:text-base text-gray-200 font-light">
        <li><a href="#" class="hover:text-white hover:underline transition">Nuestros socios</a></li>
        <li><a href="#" class="hover:text-white hover:underline transition">Condiciones generales</a></li>
      </ul>
    </div>
  </div>
</footer>

<script>
  //Animación menu navbar
  let lastScrollTop = 0;
  const nav = document.getElementById("smart-nav");

  window.addEventListener("scroll", function () {
    let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (nav) {
      if (scrollTop > lastScrollTop && scrollTop > 50) {
        nav.style.transform = "translateY(-100%)";
      } else {
        nav.style.transform = "translateY(0)";
      }
    }
    lastScrollTop = scrollTop;
  });

  //Animación Menu lateral
  document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.getElementById("menu-toggle");
    const closeMenu = document.getElementById("close-menu");
    const sidebar = document.getElementById("sidebar-menu");
    const overlay = document.getElementById("menu-overlay");
    let isMenuOpen = false;

    function toggleMenu() {
      isMenuOpen = !isMenuOpen;
      if (isMenuOpen) {
        const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
        document.body.style.paddingRight = `${scrollbarWidth}px`;
        document.body.style.overflow = "hidden";
        sidebar.classList.remove("-translate-x-full");
        overlay.classList.remove("hidden");
        setTimeout(() => overlay.classList.remove("opacity-0"), 10);
      } else {
        sidebar.classList.add("-translate-x-full");
        overlay.classList.add("opacity-0");
        setTimeout(() => overlay.classList.add("hidden"), 300);
        document.body.style.overflow = "";
        document.body.style.paddingRight = "0px";
      }
    }

    if (menuToggle) menuToggle.addEventListener("click", toggleMenu);
    if (closeMenu) closeMenu.addEventListener("click", toggleMenu);
    if (overlay) overlay.addEventListener("click", toggleMenu);
  });

  // Login Card Toggle Logic
  const userBtn = document.getElementById("user-menu-btn");
  const loginCard = document.getElementById("login-card");

  if (userBtn && loginCard) {
    userBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      const isClosed = loginCard.classList.contains("invisible");
      if (isClosed) {
        loginCard.classList.remove("invisible", "opacity-0", "scale-95", "pointer-events-none");
      } else {
        loginCard.classList.add("invisible", "opacity-0", "scale-95", "pointer-events-none");
      }
    });

    document.addEventListener("click", (e) => {
      if (!loginCard.contains(e.target) && !userBtn.contains(e.target)) {
        loginCard.classList.add("invisible", "opacity-0", "scale-95", "pointer-events-none");
      }
    });

    loginCard.addEventListener("click", (e) => {
      e.stopPropagation();
    });
  }

  // Password visibility toggle
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function (e) {
      e.stopPropagation(); 
      const targetId = this.getAttribute('data-target');
      const input = document.getElementById(targetId);
      const eyePaths = this.querySelectorAll('.eye');
      const eyeOffPaths = this.querySelectorAll('.eye-off');

      if (input.type === 'password') {
        input.type = 'text';
        eyePaths.forEach(p => p.classList.add('hidden'));
        eyeOffPaths.forEach(p => p.classList.remove('hidden'));
      } else {
        input.type = 'password';
        eyePaths.forEach(p => p.classList.remove('hidden'));
        eyeOffPaths.forEach(p => p.classList.add('hidden'));
      }
    });
  });
</script>
