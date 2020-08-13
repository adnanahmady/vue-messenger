<template>
    <div class="list-group">
        <div
            v-for="(contact) in sortedContacts"
            :key="contact.id"
            class="list-group-item cursor-pointer"
            @click="selectContact(contact)"
            :class="`list-group-item-${selectedIndex === contact.id ? 'info' : 'light'}`"
        >
            <div class="row">
                <div class="col-4 col-sm-12 col-md-5 col-lg-4 position-relative">
                    <img :src="contact.profile_image" alt="avatar" class="rounded-circle img-thumbnail">
                    <span
                        class="position-absolute unread badge badge-danger"
                        v-if="unreadMessages(contact)"
                        v-text="unreadMessages(contact)"
                    ></span>
                </div>
                <div class="col-8 col-sm-12 col-md-7 col-lg-8 d-flex flex-column justify-content-around">
                    <div class="row">
                        <div class="col">
                            <span v-text="contact.name"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span v-text="contact.email"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ContactsList",
        props: {
            contacts: {
                type: Array,
                default: []
            },

            user: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                selectedIndex: null,
                unread: 'unread_messages_count',
                allContacts: [],
            }
        },

        methods: {
            /**
             * Sets selected contact as target
             * contact to chat with.
             *
             * @param contact Contact.
             */
            selectContact: function (contact) {
                this.selectedIndex = contact.id;

                this.$emit('contact-selected', contact);
            },

            /**
             * Returns unread messages count item
             * value.
             *
             * @param message Messages sends field.
             *
             * @returns int
             */
            unreadMessages: function ({sends}) {
                return sends.length && [...sends].shift()[this.unread];
            }
        },

        computed: {
            /**
             * Sorts contacts
             *
             * @returns {contacts[]}
             */
            sortedContacts: function () {
                return _.sortBy(this.contacts, [(contact) => {
                        if (contact.id === this.selectedIndex) {
                            return Infinity;
                        }

                        return contact.sends.length && [...contact.sends].shift()[this.unread]
                    }]).reverse();
            }
        }
    }
</script>

<style scoped>
    .unread {
        right: 0;
        bottom: .4rem;
    }
</style>
