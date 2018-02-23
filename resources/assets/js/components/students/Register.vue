<template>
    <div class="students">
        <div class="container">
            <br><br>
            <div class="row">
                <div class="col-md-4">
                    <img src="img/logos/logo-universidad-construrama.png" class="img-responsive" alt="">
                </div>
                <div class="col-md-8">
                    <p>Formato de registro de alta de alumno para participar en la Universidad Construrama. Cada nuevo Construrama tiene acceso a tres Diplomados de Capacitación.</p>
                    <p>Información de Inscripción cualquier duda favor de contactar a <a href="mailto:uconstrurama@cemex.com">uconstrurama@cemex.com</a></p>
                </div>
            </div>
            <br><br><br>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" :class="{'has-error': student.error.name}">
                        <label><span class="text-danger">*</span> Nombre (s)</label>
                        <input type="text" class="form-control"
                            v-model="student.data.name">
                        <span class="help-block" v-if="student.error.name">{{student.error.name[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.last_name}">
                        <label><span class="text-danger">*</span> Apellido (s)</label>
                        <input type="text" class="form-control"
                            v-model="student.data.last_name">
                        <span class="help-block" v-if="student.error.last_name">{{student.error.last_name[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.gender_id}">
                        <label><span class="text-danger">*</span> Sexo</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.gender_id">
                            <option v-for="option in lists.genders"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.gender_id">{{student.error.gender_id[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.birthday}">
                        <label><span class="text-danger">*</span> Fecha de nacimiento</label>
                        <input type="date" class="form-control"
                            v-model="student.data.birthday">
                        <span class="help-block" v-if="student.error.birthday">{{student.error.birthday[0]}}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" :class="{'has-error': student.error.study_id}">
                        <label><span class="text-danger">*</span> Nivel de Estudio Actual</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.study_id">
                            <option v-for="option in lists.studies"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.study_id">{{student.error.study_id[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.position_id}">
                        <label><span class="text-danger">*</span> Puesto Actual</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.position_id">
                            <option v-for="option in lists.positions"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.position_id">{{student.error.position_id[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.years_in_position}">
                        <label><span class="text-danger">*</span> Años de antiguedad en el puesto</label>
                        <input type="number" class="form-control"
                            v-model="student.data.years_in_position">
                        <span class="help-block" v-if="student.error.years_in_position">{{student.error.years_in_position[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.certificate_id}">
                        <label><span class="text-danger">*</span> Diplomado al que Aplica</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.certificate_id">
                            <option v-for="option in lists.certificates"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.certificate_id">{{student.error.certificate_id[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.payment_method_id}">
                        <label><span class="text-danger">*</span> Forma de pago</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.payment_method_id">
                            <option v-for="option in lists.paymentMethods"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.payment_method_id">{{student.error.payment_method_id[0]}}</span>
                    </div>
                </div>
            </div>
            <br><hr>
            <div class="row">
                <div class="col-md-12">
                    <h4>Informacón de contacto</h4>
                    <br>
                </div>
                <div class="col-md-6">
                    <div class="form-group" :class="{'has-error': student.error.cellphone}">
                        <label><span class="text-danger">*</span> Teléfono Celular</label>
                        <input type="string" class="form-control"
                            v-model="student.data.cellphone">
                        <span class="help-block" v-if="student.error.cellphone">{{student.error.cellphone[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.phone}">
                        <label><span class="text-danger">*</span> Teléfono Fijo</label>
                        <input type="string" class="form-control"
                            v-model="student.data.phone">
                        <span class="help-block" v-if="student.error.phone">{{student.error.phone[0]}}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" :class="{'has-error': student.error.email}">
                        <label><span class="text-danger">*</span> Correo electrónico</label>
                        <input type="email" class="form-control"
                            v-model="student.data.email">
                        <span class="help-block" v-if="student.error.email">{{student.error.email[0]}}</span>
                    </div>
                    <div class="form-group" :class="{'has-error': student.error.email_confirmation}">
                        <label><span class="text-danger">*</span> Confirma correo electrónico</label>
                        <input type="email" class="form-control"
                            v-model="student.data.email_confirmation">
                        <span class="help-block" v-if="student.error.email_confirmation">{{student.error.email_confirmation[0]}}</span>
                    </div>
                </div>
            </div>
            <br><hr>
            <div class="row">
                <div class="col-md-12">
                    <h4>Informacón Construrama</h4>
                    <br>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.state_id}">
                        <label><span class="text-danger">*</span> Estado</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.state_id">
                            <option v-for="option in lists.states"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.state_id">{{student.error.state_id[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.city_id}">
                        <label><span class="text-danger">*</span> Ciudad</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.city_id">
                            <option v-for="option in this.lists.cities"
                                :value="option.id">
                                {{ option.name }} - {{ option.leader_name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.city_id">{{student.error.city_id[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.store_id}">
                        <label><span class="text-danger">*</span> Construrama ({{this.lists.stores.length}})</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.store_id">
                            <option v-for="option in lists.stores"
                                :value="option.id">
                                {{ option.name }}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.store_id">{{student.error.store_id[0]}}</span>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-success"
                            @click="registerStudent">Guardar</button>
                </div>
            </div>
            <br><br><br>
        </div>
    </div>
</template>

<script>
import swal from 'sweetalert';
export default {
    data() {
        return {
            lists: {
                certificates: [],
                genders: [],
                paymentMethods: [],
                positions: [],
                studies: [],
                states: [],
                cities: [],
                stores: []
            },
            student: {
                data: {},
                error: {}
            }
        }
    },
    mounted () {
        this.getLists()
    },
    watch: {
        'student.data.state_id' (val) {
            if (val) {
                axios.get('/api/students/lists/cities/' + val)
                .then(response => {
                    this.lists.cities = response.data
                })
            }
        },
        'student.data.city_id' (val) {
            if (val) {
                axios.get('/api/students/lists/stores/' + val)
                .then(response => {
                    this.lists.stores = response.data
                })
            }
        }
    },
    methods: {
        getLists () {
            axios.get('/api/students/lists/certificates')
            .then(response => {
                this.lists.certificates = response.data
            })
            axios.get('/api/students/lists/genders')
            .then(response => {
                this.lists.genders = response.data
            })
            axios.get('/api/students/lists/paymentMethods')
            .then(response => {
                this.lists.paymentMethods = response.data
            })
            axios.get('/api/students/lists/positions')
            .then(response => {
                this.lists.positions = response.data
            })
            axios.get('/api/students/lists/studies')
            .then(response => {
                this.lists.studies = response.data
            })
            axios.get('/api/students/lists/states')
            .then(response => {
                this.lists.states = response.data
            })
        },
        registerStudent (e) {
            var btn = $(e.target).button('loading')
            axios.post('/api/students/register', this.student.data)
            .then(response => {
                this.student = {
                    data: {},
                    error: {}
                };
                var btn = $(e.target).button('reset')
                swal("Registro Exitoso", "Tu registro se realizo de manera exitosa", "success")
            })
            .catch(error => {
                this.student.error = error.response.data;
                var btn = $(e.target).button('reset')
            });
        }
    }
}
</script>
