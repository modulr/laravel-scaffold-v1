<template lang="html">
    <div class="row">
        <div class="col-sm-3 profile-sidebar">
            <profile-sidebar-edit :user="user"></profile-sidebar-edit>
        </div>
        <div class="col-sm-9 profile-content">
            <!-- personal -->
            <profile-personal-edit :user="user"></profile-personal-edit>
            <!-- contact -->
            <profile-contact-edit :user="user" id="contact"></profile-contact-edit>
            <!-- education -->
            <profile-education-edit :user="user" id="education"></profile-education-edit>
            <!-- family -->
            <profile-family-edit :user="user" id="family"></profile-family-edit>
            <!-- places -->
            <profile-place-edit :user="user" id="place"></profile-place-edit>
        </div>
    </div>
</template>

<script>
    import ProfileSidebarEdit from './Sidebar.vue';
    import ProfilePersonalEdit from './Personal.vue';
    import ProfileContactEdit from './Contact.vue';
    import ProfileEducationEdit from './Education.vue';
    import ProfileFamilyEdit from './Family.vue';
    import ProfilePlaceEdit from './Place.vue';

    export default {
        props:['user'],
    }
</script>
