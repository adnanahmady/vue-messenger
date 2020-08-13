<template>
    <h1 v-text="contact ? contact.name : 'Please choose a conversation...'"></h1>
</template>

<script>
    export default {
        name: "ConversationTitle",

        props: {
            contact: {
                type: Object,
                default: {}
            }
        }
    }
</script>

<style scoped>

</style>
