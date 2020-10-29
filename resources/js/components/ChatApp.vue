<template>
    <div class="row">
        <div class="col-12 col-sm-7 col-lg-8">
            <conversations :contact="selectedContact" :messages="messages" @new-message="newMessage" />
        </div>
        <div class="col-12 col-sm-5 col-lg-4 page-fit scrollable">
            <contacts-list :contacts="contacts" :user="user" @contact-selected="startConversationWith" />
        </div>
    </div>
</template>

<script>
    import Conversations from './Conversations';
    import ContactsList from './ContactsList';

    export default {
        props: {
            user: {
                type: Object,
                required: true
            }
        },

        components: {Conversations, ContactsList},

        data() {
            return {
                selectedContact: null,
                messages: [],
                contacts: [],
                unread: 'unread_messages_count',
            };
        },

        mounted() {
            Echo.private(`contacts.${this.user.id}`)
                .listen('NewMessage', ({message}) => {
                    this.handleIncomingMessages(message)
                });

            this.getContacts();
        },

        methods: {
            /**
             * Gets contacts from server and sets contacts
             * property.
             */
            getContacts: async function () {
                try {
                    const { data } = await api.get('/contacts')

                    this.contacts = data;
                } catch (ex) {
                    console.log({ex});
                }
            },

            /**
             * Adds given message to messages array.
             */
            newMessage: function (message) {
                this.messages.push(message);
            },

            /**
             * Gets conversations with given contact.
             *
             * @param contact Contact to chat with.
             */
            startConversationWith: async function (contact) {
                try {
                    const {data} = await api.get(`/contacts/${contact.id}/conversations`);

                    this.messages = data;
                    this.selectedContact = contact;

                    this.updateUnreadCount(contact, true);
                } catch (ex) {
                    console.log({ex});
                }
            },

            /**
             * Handles new messages received through
             * socket port (pusher server).
             *
             * @param message Incoming message.
             */
            handleIncomingMessages: function (message) {
                if (this.selectedContact && this.selectedContact.id === message.from) {
                    return this.newMessage(message);
                }

                this.updateUnreadCount(message.from_contact, false);
            },

            /**
             * Marks contact unread messages count field
             * as read.
             *
             * @param contact Target contact.
             * @param markAsRead boolean Mark as read flag.
             */
            updateUnreadCount: function (contact, markAsRead) {
                this.contacts.map(c => {
                    if (c.id !== contact.id) {
                        return c;
                    }

                    if (c.sends.length) {
                        markAsRead ?
                            c.sends[0][this.unread] = 0 :
                            c.sends[0][this.unread] += 1;
                    }

                    return c;
                });
            }
        }
    }
</script>
