<template>
    <div class="flex items-center justify-center min-h-screen bg-custom-pattern">
        <div class="container mx-auto p-4">
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-center">
                    <logo-andreatex container-class="w-64 h-24" />
                </div>
            </div>
            <form @submit.prevent="submitLogin" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="form__group mb-4">
                    <input 
                        type="text" 
                        v-model="Nombre" 
                        class="form__field" 
                        id="Nombre" 
                        placeholder=" " 
                        required 
                    />
                    <label for="Nombre" class="form__label">Nombre</label>
                </div>
                <div class="form__group mb-4 relative">
                    <input 
                        :type="showPassword ? 'text' : 'password'" 
                        v-model="Contraseña" 
                        class="form__field" 
                        id="Contraseña" 
                        placeholder=" " 
                        required 
                    />
                    <label for="Contraseña" class="form__label">Contraseña</label>
                    <i 
                        @click="togglePassword" 
                        :class="showPassword ? 'fa fa-eye-slash' : 'fa fa-eye'" 
                        class="absolute right-2 top-8 cursor-pointer text-gray-500">
                    </i>
                </div>
                <button 
                    type="submit" 
                    class="text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline" 
                    style="background-color: #0047AB;">
                    Iniciar sesión
                </button>
            </form>
            <p v-if="error" class="text-red-500 text-xs italic text-center">{{ error }}</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import LogoAndreatex from './LogoAndreatex.vue'

export default {
    data() {
        return {
            Nombre: '',
            Contraseña: '',
            showPassword: false,
            error: null,
        };
    },

    components: {
        LogoAndreatex
    },

    methods: {
        togglePassword() {
            this.showPassword = !this.showPassword;
        },
        async submitLogin() {
            try {
                const response = await axios.post('/vendedor/login', {
                    Nombre: this.Nombre,
                    Contraseña: this.Contraseña,
                });

                if (response.data.success) {
                    this.error = null;

                    // Redirigir según el rol del usuario
                    if (response.data.role === 'Admin') {
                        this.$router.push({ name: 'AdminDashboard' });
                    } else if (response.data.role === 'Vendedor') {
                        this.$router.push('/vendedor-dashboard');
                    }
                }
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    this.error = error.response.data.error;
                } else {
                    this.error = "Error en la comunicación con el servidor.";
                }
            }
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 400px;
    padding: 20px;
    border-radius: 5px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Fondo personalizado */
.bg-custom-pattern {
    width: 100%;
    height: 100%;
    --color: #E1E1E1;
    background-color: #F3F3F3;
    background-image: linear-gradient(
            0deg,
            transparent 24%,
            var(--color) 25%,
            var(--color) 26%,
            transparent 27%,
            transparent 74%,
            var(--color) 75%,
            var(--color) 76%,
            transparent 77%,
            transparent
        ),
        linear-gradient(
            90deg,
            transparent 24%,
            var(--color) 25%,
            var(--color) 26%,
            transparent 27%,
            transparent 74%,
            var(--color) 75%,
            var(--color) 76%,
            transparent 77%,
            transparent
        );
    background-size: 55px 55px;
}

.form__group {
    position: relative;
    padding: 20px 0 0;
    width: 100%;
}

.form__field {
    font-family: inherit;
    width: 100%;
    border: none;
    border-bottom: 2px solid #9b9b9b;
    outline: 0;
    font-size: 17px;
    color: #000;
    padding: 7px 0;
    background: transparent;
    transition: border-color 0.2s;
}

.form__field::placeholder {
    color: transparent;
}

.form__field:placeholder-shown ~ .form__label {
    font-size: 17px;
    cursor: text;
    top: 20px;
}

.form__label {
    position: absolute;
    top: 0;
    display: block;
    transition: 0.2s;
    font-size: 17px;
    color: #9b9b9b;
    pointer-events: none;
}

.form__field:focus {
    padding-bottom: 6px;
    border-width: 3px;
    border-image: linear-gradient(to right, #0047AB, #c5d0ff);
    border-image-slice: 1;
}

.form__field:focus ~ .form__label {
    top: 0;
    font-size: 17px;
    color: #c5d0ff;
    font-weight: 700;
}

.fa-eye,
.fa-eye-slash {
    font-size: 1.2em;
}
</style>
