<template>
    <div class="auth-modal">
        <div class="auth-container">
            <div class="auth-header">
                <h2>{{ isLogin ? 'Login' : 'Register' }}</h2>
                <button class="close-button" @click="$emit('close')">×</button>
            </div>

            <div class="auth-tabs">
                <button :class="{ active: isLogin }" @click="isLogin = true">
                    Login
                </button>
                <button :class="{ active: !isLogin }" @click="isLogin = false">
                    Register
                </button>
            </div>

            <form @submit.prevent="submitForm">
                <div class="form-group" v-if="!isLogin">
                    <label for="email">Email</label>
                    <input type="email" id="email" v-model="form.email" required placeholder="Enter your email" />
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" v-model="form.username" required
                        placeholder="Enter your username" />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" v-model="form.password" required
                        placeholder="Enter your password" />
                </div>

                <div v-if="error" class="error-message">
                    {{ error }}
                </div>

                <div class="form-actions">
                    <button type="button" class="cancel-button" @click="$emit('close')">
                        Cancel
                    </button>
                    <button type="submit" class="submit-button" :disabled="isSubmitting">
                        {{ isSubmitting ? 'Processing...' : (isLogin ? 'Login' : 'Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, inject } from 'vue'

/**
 * Auth form component for login, registration, and password reset
 * 
 * @emits login - Emitted when user successfully logs in
 * @emits register - Emitted when user successfully registers
 */

const emit = defineEmits(['login', 'register', 'close'])

// Injected values
const isDevelopmentMode = inject('isDevelopmentMode', ref(false))
const apiUrl = inject('apiUrl', '/backend')

// State
const isLogin = ref(true)
const isSubmitting = ref(false)
const error = ref('')

// Form data
const form = reactive({
    username: '',
    email: '',
    password: ''
})

/**
 * Submit the form
 */
const submitForm = async () => {
    error.value = ''
    isSubmitting.value = true

    try {
        // Check if we're in development mode
        if (isDevelopmentMode.value) {
            // Simulate API call in development mode
            await new Promise(resolve => setTimeout(resolve, 500))

            // Create a mock user
            const userData = {
                id: 1,
                username: form.username,
                email: form.email || `${form.username}@example.com`
            }

            // Emit the appropriate event
            if (isLogin.value) {
                emit('login', userData)
            } else {
                emit('register', userData)
            }

            return
        }

        // Determine API endpoint
        const endpoint = isLogin.value ? `${apiUrl}/api/login.php` : `${apiUrl}/api/register.php`

        // Prepare request data
        const requestData = {
            username: form.username,
            password: form.password
        }

        // Add email for registration
        if (!isLogin.value) {
            requestData.email = form.email
        }

        // Make API request
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData),
            credentials: 'include' // Include cookies in the request
        })

        // Check if response is JSON
        const contentType = response.headers.get('content-type')
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Server returned an invalid response')
        }

        const data = await response.json()

        if (data.status === 'success') {
            // Emit the appropriate event
            if (isLogin.value) {
                emit('login', data.user)
            } else {
                emit('register', data.user)
            }
        } else {
            error.value = data.message || 'An error occurred'
        }
    } catch (err) {
        console.error('Auth error:', err)
        error.value = 'An error occurred. Please try again.'
    } finally {
        isSubmitting.value = false
    }
}
</script>

<style scoped>
.auth-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.auth-container {
    width: 100%;
    max-width: 400px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.auth-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
}

.auth-header h2 {
    margin: 0;
    color: #333;
    font-size: 1.5rem;
}

.close-button {
    background: none;
    border: none;
    font-size: 24px;
    color: #999;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.auth-tabs {
    display: flex;
    border-bottom: 1px solid #eee;
}

.auth-tabs button {
    flex: 1;
    padding: 12px;
    background: none;
    border: none;
    font-size: 16px;
    cursor: pointer;
    color: #666;
    transition: all 0.3s ease;
}

.auth-tabs button.active {
    color: #2196f3;
    font-weight: 500;
    box-shadow: inset 0 -2px 0 #2196f3;
}

form {
    padding: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #555;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    height: 40px;
}

.error-message {
    color: #f44336;
    margin-bottom: 15px;
    font-size: 14px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.cancel-button {
    padding: 10px 16px;
    background-color: #f5f5f5;
    color: #555;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    height: 40px;
}

.submit-button {
    padding: 10px 16px;
    background-color: #2196f3;
    color: white;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    height: 40px;
}

.submit-button:hover {
    background-color: #1976d2;
}

.submit-button:disabled {
    background-color: #b0bec5;
    cursor: not-allowed;
}
</style>