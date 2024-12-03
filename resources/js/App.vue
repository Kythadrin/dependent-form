<style>
    .loader {
        border-width: 2px;
        border-style: solid;
        border-radius: 50%;
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Register</h1>
            <form @submit.prevent="submitForm" class="space-y-4">
                <Input
                    id="name"
                    label="Name:"
                    v-model="form.name"
                    :errors="errors.name"
                />
                <Input
                    id="email"
                    label="Email:"
                    v-model="form.email"
                    :errors="errors.email"
                />
                <Input
                    id="password"
                    label="Password:"
                    type="password"
                    v-model="form.password"
                    :errors="errors.password"
                />
                <Input
                    id="passwordConfirmation"
                    label="Confirm Password:"
                    type="password"
                    v-model="form.passwordConfirmation"
                    :errors="errors.passwordConfirmation"
                />
                <Select
                    id="country"
                    label="Country:"
                    v-model="form.country"
                    :options="countries"
                    :errors="errors.country"
                    :loading="loadingCountries"
                    placeholder="Select a country"
                    @update:modelValue="fetchLanguages"
                />
                <Select
                    id="language"
                    label="Language:"
                    v-model="form.language"
                    :options="languages"
                    :errors="errors?.language"
                    :loading="loadingLanguages"
                    :disabled="!languages.length || loadingLanguages"
                    placeholder="Select a language"
                />
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition flex items-center justify-center"
                >
                    <span v-if="loading" class="loader w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></span>
                    <span>{{ loading ? "Submitting..." : "Register" }}</span>
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import Input from '@/components/Input.vue';
import Select from '@/components/Select.vue';

export default {
    components: {
        Input,
        Select,
    },
    data() {
        return {
            countries: [],
            languages: [],
            form: {
                name: "",
                email: "",
                password: "",
                passwordConfirmation: "",
                country: "",
                language: "",
            },
            errors: {
                name: [],
                email: [],
                password: [],
                passwordConfirmation: [],
                country: [],
                language: [],
            },
            loadingCountries: false,
            loadingLanguages: false,
            loading: false,
        };
    },
    methods: {
        async fetchCountries() {
            this.loadingCountries = true;
            try {
                const response = await axios.get("/country");
                this.countries = response.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.loadingCountries = false;
            }
        },
        async fetchLanguages() {
            if (!this.form.country) return;

            this.loadingLanguages = true;
            try {
                const response = await axios.get(`/language/${this.form.country}`);
                this.languages = response.data;
            } catch (error) {
                if (error.response?.data?.errors) {
                    this.errors = error.response.data.errors;
                } else {
                    console.error(error);
                }
            } finally {
                this.loadingLanguages = false;
            }
        },
        validateForm() {
            if(!this.form.name) {
                this.errors.name.push("Name required");
            }
            if(!this.form.email) {
                this.errors.email.push("Email required");
            } else if (this.validEmail(this.form.email)) {
                this.errors.email.push("Email not valid");
            }
            if(!this.form.password) {
                this.errors.password.push("Password required");
            } else if (this.form.password.length < 8) {
                this.errors.password.push("The password field must be at least 8 characters.");
            }
            if(!this.form.passwordConfirmation) {
                this.errors.passwordConfirmation.push("Confirm password required");
            }
            if (this.form.password !== this.form.passwordConfirmation) {
                this.errors.passwordConfirmation.push("Passwords do not match");
            }
            if (!this.form.country) {
                this.errors.country.push("Country required");
            }
            if (!this.form.language) {
                this.errors.language.push("Language required");
            }
        },
        validEmail: function (email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return !regex.test(email);
        },
        hasErrors: function () {
            return Object.values(this.errors).some(errorArray => errorArray.length > 0)
        },
        async submitForm() {
            this.errors = {
                name: [],
                email: [],
                password: [],
                passwordConfirmation: [],
                country: [],
                language: [],
            }

            this.validateForm();

            if (!this.hasErrors()) {
                this.loading = true;
                try {
                    const response = await axios.post("/register", this.form);
                    console.log("Form submitted successfully:", response.data);
                } catch (error) {
                    if (error.response?.data?.errors) {
                        this.errors = error.response.data.errors;
                    } else {
                        console.error(error);
                    }
                } finally {
                    this.loading = false;
                }
            }
        },
    },
    mounted() {
        this.fetchCountries();
    },
};
</script>
