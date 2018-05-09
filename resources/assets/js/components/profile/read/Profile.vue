<template lang="html">
    <div>
        <!-- Personal -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Perfil
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <dl>
                            <dd>Edad</dd>
                            <dt>
                                {{user.age}}
                            </dt>
                        </dl>
                        <dl>
                            <dd>Descripción</dd>
                            <dt>
                                {{user.description}}
                            </dt>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Contacto
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

        <!-- Places -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Ubicación
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
