<template>
    <div class="row page-fit">
        <div class="col d-flex flex-column justify-content-between">
            <div class="row">
                <div class="col">
                    <conversation-title :contact="contact"></conversation-title>
                </div>
            </div>
            <div class="row my-3 flex-fill rounded">
                <div class="col bg-light border" v-cloak>
                    <messages-feed :contact="contact" :messages="messages"></messages-feed>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <message-composer @send="sendMessage"></message-composer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ConversationTitle from "./ConversationTitle";
    import MessagesFeed from "./MessagesFeed";
    import MessageComposer from "./MessageComposer";

    export default {
        name: "Conversations",

        components: {
            ConversationTitle,
            MessagesFeed,
            MessageComposer
        },

        props: {
            contact: {
                type: Object,
                default: {}
            },
            messages: {
                type: Array,
                default: []
            }
        },

        methods: {
            /**
             * Sends message and emits response to
             * upper component.
             *
             * @param text Message text.
             */
            async sendMessage(text) {
                if (!this.contact) return false;

                try {
                    const body = {contact: this.contact.id, text};
                    const { data } = await api.post(`/conversations`, body);

                    this.$emit('new-message', data);
                } catch (ex) {
                    console.log({ex});
                }
            }
        }
    }
</script>

