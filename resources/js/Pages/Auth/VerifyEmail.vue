<template>
<div>
    <div class="mb-4 text-sm text-muted">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
    </div>

    <div class="alert alert-success mb-4" v-if="verificationLinkSent" >
        A new verification link has been sent to the email address you provided during registration.
    </div>

    <form @submit.prevent="submit">
        <div class="float-end">
            <Link :href="route('logout')" method="post" class="text-dark">Log Out</Link>

            <button type="submit" class="btn btn-dark ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Resend Verification Email
            </button>
        </div>
    </form>
</div>
</template>

<script>
    import BreezeGuestLayout from "@/Layouts/Guest"
    import { Link } from '@inertiajs/inertia-vue'


    export default {
        layout: BreezeGuestLayout,

        components: {
            Link
        },

        props: {
            status: String,
        },

        data() {
            return {
                form: this.$inertia.form()
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('verification.send'))
            },
        },

        computed: {
            verificationLinkSent() {
                return this.status === 'verification-link-sent';
            }
        }
    }
</script>
