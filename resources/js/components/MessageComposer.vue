<template>
    <div class="row">
        <div class="col-10">
            <textarea
                v-model="text"
                class="form-control"
                @keyup.enter.ctrl.prevent="compose"
                placeholder="Message..."
            ></textarea>
        </div>
        <div class="col-2 d-flex">
            <button class="btn btn-link flex-fill rounded-circle" @click="compose">
                <i class="fa fa-send fa-2x text-danger" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MessageComposer",

        data() {
            return {
                text: '',
            }
        },

        methods: {
            /**
             * Sends message text to upper component
             * for handling.
             *
             * @returns {boolean}
             */
            compose: function () {
                if (this.text.length === 0) return false;

                this.$emit('send', this.text);
                this.text = '';
            }
        }
    }
</script>

<style scoped>

</style>
