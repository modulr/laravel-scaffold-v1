<template lang="html">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Perfil</h3>
        </div>
        <div class="panel-body">
            <form @submit.prevent>
                <div class="form-group" :class="{'has-error': error.name}">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control"
                        v-model="user.name">
                    <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                </div>
                <div class="form-group" :class="{'has-error': error.age}">
                    <label class="control-label">Edad</label>
                    <input type="text" class="form-control"
                        v-model="user.age">
                    <span class="help-block" v-if="error.age">{{error.age[0]}}</span>
                </div>
                <div class="form-group" :class="{'has-error': error.description}">
                    <label class="control-label">Descripción</label>
                    <textarea rows="3" class="form-control" v-model="user.description"></textarea>
                    <span class="help-block" v-if="error.description">{{error.description[0]}}</span>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" @click="updatePersonal">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import {Snotify} from 'vue-snotify';
    import {SnotifyService} from 'vue-snotify';
    Vue.use(Snotify)

    export default {
        data() {
            return {
                error: {}
            }
        },
        props: ['user'],
        methods: {
            updatePersonal (e) {
                var btn = $(e.target).button('loading')
                axios.put('/api/profile/update/'+this.user.id, this.user)
                .then(response => {
                    this.user = response.data;
                    this.error = {};
                    btn.button('reset')
                    SnotifyService.success('The profile has been saved success!')
                })
                .catch(error => {
                    this.error = error.response.data;
                    btn.button('reset')
                });
            }
        }
    }
</script>
