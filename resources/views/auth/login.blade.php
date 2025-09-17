<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lisa Wangi | Login</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap');

        body {
            font-family: 'Montserrat', sans-serif;
        }

        .perfume-drops {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .drop {
            position: absolute;
            bottom: 100%;
            background: linear-gradient(to bottom, rgba(255, 240, 230, 0.8), rgba(180, 140, 100, 0.6));
            border-radius: 10px 10px 10px 0;
            animation: drip 4s infinite ease-in;
            transform: rotate(45deg);
            opacity: 0;
        }

        @keyframes drip {
            0% {
                bottom: 100%;
                opacity: 0;
            }

            15% {
                opacity: 1;
            }

            100% {
                bottom: -20px;
                opacity: 0;
            }
        }
    </style>
</head>

<body class="min-h-screen relative">

    <!-- Background Gradient -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[rgb(244,237,228)] via-[rgb(222,201,181)] to-[rgb(180,140,100)]"></div>
    <!-- <div class="absolute inset-0 -z-10 bg-gradient-to-br from-[rgb(210,190,170)] via-[rgb(160,120,90)] to-[rgb(100,70,40)]"></div> -->


    <!-- Perfume Drops -->
    <div class="perfume-drops" id="drops-container"></div>

    <div class="container mx-auto px-4 py-12 flex flex-col lg:flex-row items-center justify-center min-h-screen">

        <!-- Branding -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center mb-12 lg:mb-0 lg:pr-12 animate__animated animate__fadeInLeft">
            <img src="{{ asset('images/lisawb.jpeg') }}" alt="Logo" class="w-32 h-32 rounded-full shadow-md mb-6">
            <h1 class="text-5xl font-bold font-serif tracking-wide text-[#3e2b1f]">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#8b5e3c] to-[#d2a679]">LISA</span> WANGI
            </h1>
            <p class="text-[#6f4e37] text-center mt-4 max-w-md">
                <!-- Discover your signature scent from our exclusive collection of luxury fragrances. -->
                Discover your signature scent from our exclusive collection of luxury fragrances. Let each note whisper confidence, elegance, and unforgettable charm.
                <!-- Temukan aroma khas Anda dari koleksi parfum mewah eksklusif kami. Biarkan setiap notanya membisikkan kepercayaan diri, keanggunan, dan pesona yang tak terlupakan. -->
            </p>
        </div>

        <!-- Login Form -->
        <div class="w-full lg:w-1/2 flex justify-center animate__animated animate__fadeInRight">
            <div class="w-full max-w-lg p-10 rounded-2xl border border-[#d7bfa6] bg-white/70 backdrop-blur-md shadow-xl transition-all duration-300 hover:shadow-2xl">
                <h2 class="text-3xl font-serif font-semibold text-[#3e2b1f] text-center mb-1">Welcome Back</h2>
                <p class="text-[#92725d] text-center mb-6">Silahkan login ke akun Anda</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-[#543f32] mb-1">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-[#6b4226]">
                                <!-- Icon Mail --> 
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input type="email" name="email" id="email" required
                                placeholder="Masukkan email"
                                value="{{ old('email') }}"
                                class="block w-full pl-10 pr-4 py-2 rounded-md border-gray-300 focus:border-[#6b4226] focus:ring-[#6b4226] sm:text-sm">
                        </div>
                        @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#543f32] mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-[#6b4226]">
                                <!-- Lock Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 11c1.104 0 2-.896 2-2V7a2 2 0 00-4 0v2c0 1.104.896 2 2 2zm6 0v8a2 2 0 01-2 2H8a2 2 0 01-2-2v-8h12z" />
                                </svg>
                            </span>
                            <input type="password" name="password" id="password" required
                                placeholder="Masukkan password"
                                class="block w-full pl-10 pr-10 py-2 rounded-md border-gray-300 focus:border-[#6b4226] focus:ring-[#6b4226] sm:text-sm">
                            <!-- Toggle Button -->
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6b4226]">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div>
                        <button type="submit"
                            class="w-full bg-[#6b4226] hover:bg-[#4a2c18] text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                            Login
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="text-center text-xs text-[#a98c74] mt-8 leading-relaxed">
                    <p>&copy; 2025 | <span class="font-serif font-semibold">Siti Rahayu_22030022</span></p>
                    <p>All Rights Reserved</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Drops Effect -->
    <script>
        const container = document.getElementById('drops-container');
        for (let i = 0; i < 15; i++) {
            const drop = document.createElement('div');
            drop.classList.add('drop');
            const left = Math.random() * 100;
            const delay = Math.random() * 5;
            const duration = 3 + Math.random() * 5;
            const size = 5 + Math.random() * 15;
            drop.style.left = `${left}%`;
            drop.style.width = `${size}px`;
            drop.style.height = `${size}px`;
            drop.style.animationDelay = `${delay}s`;
            drop.style.animationDuration = `${duration}s`;
            container.appendChild(drop);
        }
    </script>

    <!-- Show/Hide Password -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Optional: change icon based on type
            if (type === 'text') {
                eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.99 9.99 0 012.406-4.033M9.88 9.88a3 3 0 104.24 4.24" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3l18 18" />
        `;
            } else {
                eyeIcon.innerHTML = `
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
        `;
            }
        });
    </script>
</body>

</html>