<template lang="html">
    <div>
        <!-- Personal -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-address-card" aria-hidden="true"></i> Personal
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <dl>
                            <dd>Birthday</dd>
                            <dt>{{user.profile_personal.birthday | moment('from')}}</dt>
                        </dl>
                        <dl>
                            <dd>Place of birth</dd>
                            <dt>{{user.profile_personal.place_of_birth}}</dt>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dd>Gender</dd>
                            <dt v-if="user.profile_personal.gender" class="text-capitalize">
                                {{user.profile_personal.gender.title}}
                            </dt>
                        </dl>
                        <dl>
                            <dd>Relationship</dd>
                            <dt v-if="user.profile_personal.relationship" class="text-capitalize">
                                {{user.profile_personal.relationship.title}}
                            </dt>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <dl>
                            <dd>RFC</dd>
                            <dt>{{user.profile_personal.rfc}}</dt>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dd>CURP</dd>
                            <dt>{{user.profile_personal.curp}}</dt>
                        </dl>
                    </div>
                    <div class="col-md-4">
                        <dl>
                            <dd>NSS</dd>
                            <dt>{{user.profile_personal.nss}}</dt>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-address-book" aria-hidden="true"></i> Contact
                </h3>
            </div>
            <div class="panel-body">
                <dl v-for="(contact, index) in user.profile_contact">
                    <dt>
                        <i class="fa fa-fw" :class="{
                            'fa-envelope': contact.type_id == 1,
                            'fa-phone': contact.type_id == 2,
                            'fa-mobile': contact.type_id == 3,
                            }" aria-hidden="true"></i> {{contact.contact}}
                    </dt>
                </dl>
            </div>
        </div>

        <!-- Education -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-mortar-board" aria-hidden="true"></i> Education
                </h3>
            </div>
            <div class="panel-body">
                <dl v-for="(education, index) in user.profile_education">
                    <dt>{{education.title}} - {{education.school_name}}</dt>
                    <dd>
                        {{education.start_year}} - {{education.end_year}}
                        <span class="text-muted" v-show="education.description"> / {{education.description}}</span>
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Family -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-users" aria-hidden="true"></i> Family
                </h3>
            </div>
            <div class="panel-body">
                <dl v-for="(family, index) in user.profile_family">
                    <dt>{{family.name}} - {{family.relation.title}}</dt>
                    <dd>
                        {{family.gender.title}} - {{family.birthday | moment('from')}}
                        <span class="text-muted" v-show="family.description"> / {{family.description}}</span>
                    </dd>
                </dl>
            </div>
        </div>

        <!-- Places -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-fw fa-map-marker" aria-hidden="true"></i> Places
                </h3>
            </div>
            <div class="panel-body">
                <dl v-for="(place, index) in user.profile_place">
                    <dt>
                        {{place.place}}
                        <i class="fa fa-check text-success" aria-hidden="true"
                            data-toggle="tooltip" title="Currently"
                            v-show="place.currently"></i>
                    </dt>
                    <dd v-show="place.description"><span class="text-muted">{{place.description}}</span></dd>
                </dl>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user']
    }
</script>
