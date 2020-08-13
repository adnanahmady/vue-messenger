<template>
    <div class="row scrollable" ref="feed" style="height: calc(100vh - 300px);">
        <div class="col">
            <div v-for="message in messages" :key="message.id" class="row">
                <div class="col my-1" :class=itemPosition(message.from)>
                    <span
                        :class=itemColor(message.from)
                        class="rounded p-3 border border-secondary d-inline-block"
                    >
                        <span v-text="message.text" class="space-pre-line"></span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MessagesFeed",

        props: {
            contact: {
                type: Object,
                default: {}
            },

            messages: {
                type: Array,
                required: true
            }
        },

        methods: {
            /**
             * Checks if given id is equal to
             * the contacts id and if there is
             * a contact available.
             *
             * @param id Given id.
             *
             * @returns {boolean}
             */
            messageCheck: function (id) {
                return id === (this.contact && this.contact.id);
            },

            /**
             * Specifies Chats item (contact) direction
             * using bootstrap css class.
             *
             * @param id Given id.
             *
             * @returns {string}
             */
            itemPosition: function (id) {
                return `text-${this.messageCheck(id) ? 'left' : 'right'}`;
            },

            /**
             * Specifies chat item (contact) color
             * using bootstrap css class.
             *
             * @param id Given id.
             *
             * @returns {string}
             */
            itemColor: function (id) {
                return `bg-${this.messageCheck(id) ? 'light' : 'info-light'}`;
            },

            /**
             * Scrolls Chat section to bottom of the box.
             */
            scrollToBottom: function () {
                setTimeout(() => {
                    this.$refs.feed.scrollTop = this.$refs.feed.scrollHeight - this.$refs.feed.clientHeight;
                }, 50);
            }
        },
        watch: {
            /**
             * Watch's any changes on contact.
             *
             * @param contact
             */
            contact: function (contact) {
                this.scrollToBottom();
            },

            /**
             * Watch's any changes on messages.
             *
             * @param messages
             */
            messages: function (messages) {
                this.scrollToBottom();
            }
        }
    }
</script>
