<template>
    <div class="students">
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-xs-4 controls">
                        <a href="/students" class="btn btn-default">
                            <i class="fa fa-list"></i>
                            Estudiantes
                        </a>
                    </div>
                    <div class="col-xs-8 controls text-right">
                        <button type="button" class="btn btn-default"
                                @click="deleteStudent">Borrar</button>
                        <button type="button" class="btn btn-primary"
                                @click="updateStudent">Guardar</button>
                        <button type="button" class="btn btn-success"
                                data-toggle="modal" data-target="#modalVerify">Validar</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h4>Información General</h4>
                    <br>
                </div>
                <br>
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
                </div>
                <div class="col-md-6">
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
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" v-model="student.data.have_studied"> ¿Has cursado algún diplomado en la Universidad Construrama?
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" v-show="student.data.have_studied">
                        <label>¿Cual fue tu nombre de usuario?</label>
                        <input type="text" class="form-control"
                            v-model="student.data.username">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group" v-show="student.data.have_studied">
                        <label>¿En que Mes y año cursaste?</label>
                        <input type="date" class="form-control"
                            v-model="student.data.year_month">
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <h4>Información de contacto</h4>
                    <br>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.cellphone}">
                        <label><span class="text-danger">*</span> Teléfono Celular</label>
                        <the-mask class="form-control" v-model="student.data.cellphone" :mask="['(###) #######']" />
                        <span class="help-block" v-if="student.error.cellphone">{{student.error.cellphone[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.phone}">
                        <label><span class="text-danger">*</span> Teléfono Fijo</label>
                        <the-mask class="form-control" v-model="student.data.phone" :mask="['(###) #######']" />
                        <span class="help-block" v-if="student.error.phone">{{student.error.phone[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.email}">
                        <label><span class="text-danger">*</span> Correo electrónico</label>
                        <input type="email" class="form-control"
                            v-model="student.data.email">
                        <span class="help-block" v-if="student.error.email">{{student.error.email[0]}}</span>
                    </div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <h4>Información Construrama</h4>
                    <br>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.state_id}">
                        <label><span class="text-danger">*</span> Estado</label>
                        <select class="form-control text-capitalize"
                            v-model="student.data.store.state_id">
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
                            v-model="student.data.store.city_id">
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
                                {{ option.name }} - {{option.street}}
                            </option>
                        </select>
                        <span class="help-block" v-if="student.error.store_id">{{student.error.store_id[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.store_phone}">
                        <label><span class="text-danger">*</span> Teléfono</label>
                        <the-mask class="form-control" v-model="student.data.store.store_phone" :mask="['(###) #######']" />
                        <span class="help-block" v-if="student.error.store_phone">{{student.error.store_phone[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" :class="{'has-error': student.error.store_email}">
                        <label><span class="text-danger">*</span> Correo electrónico</label>
                        <input type="email" class="form-control"
                            v-model="student.data.store.store_email">
                        <span class="help-block" v-if="student.error.store_email">{{student.error.store_email[0]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Numero Tienda</label>
                        <p class="form-control">{{student.data.store.store_id}}</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Telefono</label>
                        <p class="form-control">{{student.data.store.phone}}</p>
                    </div>
                    <div class="form-group">
                        <label>Calle</label>
                        <p class="form-control">{{student.data.store.street}}</p>
                    </div>
                    <div class="form-group">
                        <label>Numero</label>
                        <p class="form-control">{{student.data.store.number}}</p>
                    </div>
                    <div class="form-group">
                        <label>Colonia</label>
                        <p class="form-control">{{student.data.store.colony}}</p>
                    </div>
                    <div class="form-group">
                        <label>CP</label>
                        <p class="form-control">{{student.data.store.cp}}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Holding</label>
                        <p class="form-control">{{student.data.store.holding}}</p>
                    </div>
                    <div class="form-group">
                        <label>Nombre Holding</label>
                        <p class="form-control">{{student.data.store.holding_name}}</p>
                    </div>
                    <div class="form-group">
                        <label>Region</label>
                        <p class="form-control">{{student.data.store.region}}</p>
                    </div>
                    <div class="form-group">
                        <label>Gerencia</label>
                        <p class="form-control">{{student.data.store.management}}</p>
                    </div>
                    <div class="form-group">
                        <label>Asesor</label>
                        <p class="form-control">{{student.data.store.advisor.name}}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>RSO</label>
                        <p class="form-control">{{student.data.store.rso}}</p>
                    </div>
                    <div class="form-group">
                        <label>RFC</label>
                        <p class="form-control">{{student.data.store.rfc}}</p>
                    </div>
                    <div class="form-group">
                        <label>Nombre Comercial</label>
                        <p class="form-control">{{student.data.store.business_name}}</p>
                    </div>
                </div>
            </div>
            <br><br><br>
        </div>
        <div class="modal fade" id="modalVerify">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Observaciones</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span class="text-danger">*</span> Es beneficio Construrama?</label>
                                    <select class="form-control text-capitalize"
                                        v-model="student.data.discount">
                                        <option :value="0">No</option>
                                        <option :value="1">Si</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><span class="text-danger">*</span> Valido que el participante es colaborador de tienda Construrama?</label>
                                    <select class="form-control text-capitalize"
                                        v-model="student.data.verified">
                                        <option :value="0">No</option>
                                        <option :value="1">Si</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><span class="text-danger">*</span> Justificación de Cambios/avisos/datos de factura</label>
                                    <textarea class="form-control" rows="2"
                                        v-model="student.data.observations"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="verifyStudent">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import swal from 'sweetalert'
export default {
    data () {
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
                data: {
                    store: {
                        city: {},
                        state: {},
                        advisor: {}
                    }
                },
                error: {}
            }
        }
    },
    props: ['id'],
    mounted () {
        this.getLists()
        this.getStudent()
    },
    watch: {
        'student.data.store.state_id' (val) {
            if (val) {
                axios.get('/api/students/lists/cities/' + val)
                .then(response => {
                    this.lists.cities = response.data
                })
            }
        },
        'student.data.store.city_id' (val) {
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
        getStudent () {
            axios.get('/api/students/show/'+this.id)
            .then(response => {
                this.student.data = response.data
            });
        },
        updateStudent (e) {
            var btn = $(e.target).button('loading')
            axios.put('/api/students/update/'+this.student.data.id, this.student.data)
            .then(response => {
                this.student.error = {};
                swal({
                    title: 'Estudiante Actualizado',
                    text: '¡El estudiante se actualizo correctamente!',
                    type: 'success'
                })
                btn.button('reset')
            })
            .catch(error => {
                this.student.error = error.response.data;
                btn.button('reset')
            });
        },
        verifyStudent (e) {
            var btn = $(e.target).button('loading')
            axios.put('/api/students/verify/'+this.student.data.id, this.student.data)
            .then(response => {
                this.student.error = {};
                swal({
                    title: 'Estudiante Validado',
                    text: '¡Se ha enviado un correo electronico al estudiante con los datos de pago!',
                    type: 'success'
                })
                btn.button('reset')
                $('#modalVerify').modal('hide')
            })
            .catch(error => {
                this.student.error = error.response.data;
                btn.button('reset')
            })
        },
        deleteStudent (e) {
            var self = this;
            var btn = $(e.target).button('loading')
            swal({
                title: "¿Estas seguro de eliminar el estudiante?",
                text: "El estudiante se borrara definitivamente!",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                closeOnConfirm: false
            },
            function(event) {
                if (event) {
                    axios.delete('/api/students/destroy/' + self.student.data.id)
                    .then(response => {
                        swal({
                            title: "Registro borrado!",
                            text: "El estudiante fue borrado.",
                            type: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        self.error = {};
                        btn.button('reset')
                        location.href = `/students`
                    })
                    .catch(error => {
                        self.student.error = error.response.data;
                        btn.button('reset')
                    });
                }
                btn.button('reset')
            });
        }
    }
}
</script>
