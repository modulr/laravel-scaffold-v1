<template lang="html">
    <div class="students">
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-md-12 controls text-right">
                        <a href="#" class="btn btn-default" @click.prevent="exportStudents">
                            <i class="fa fa-file-excel-o"></i>
                            exportar
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Sexo</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>Nivel de Estudio Actual</th>
                                    <th>Puesto Actual</th>
                                    <th>Años de antiguedad en el puesto</th>
                                    <th>Forma de pago</th>
                                    <th>Diplomado al que Aplica</th>
                                    <th>Ha cursado</th>
                                    <th>Usuario</th>
                                    <th>Teléfono Celular</th>
                                    <th>Teléfono Fijo</th>
                                    <th>Correo electrónico</th>
                                    <th>Construrama</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in students.data" :class="{'success': item.verified}">
                                    <td @click="editStudent(item.id)">{{index+1}}</td>
                                    <td @click="editStudent(item.id)">{{item.name}}</td>
                                    <td @click="editStudent(item.id)">{{item.last_name}}</td>
                                    <td @click="editStudent(item.id)">{{item.gender.name}}</td>
                                    <td @click="editStudent(item.id)">{{item.birthday}}</td>
                                    <td @click="editStudent(item.id)">{{item.study.name}}</td>
                                    <td @click="editStudent(item.id)">{{item.position.name}}</td>
                                    <td @click="editStudent(item.id)">{{item.years_in_position}}</td>
                                    <td @click="editStudent(item.id)">{{item.payment_method.name}}</td>
                                    <td @click="editStudent(item.id)">{{item.certificate.name}}</td>
                                    <td @click="editStudent(item.id)">
                                        <span v-if="item.have_studied">Si</span>
                                        <span v-else>No</span>
                                    </td>
                                    <td @click="editStudent(item.id)">{{item.username}}</td>
                                    <td @click="editStudent(item.id)">{{item.cellphone}}</td>
                                    <td @click="editStudent(item.id)">{{item.phone}}</td>
                                    <td @click="editStudent(item.id)">{{item.email}}</td>
                                    <td @click="editStudent(item.id)">{{item.store.name}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            students: {
                data: []
            }
        }
    },
    mounted () {
        this.getStudents()
    },
    methods: {
        getStudents () {
            axios.get('/api/students/all')
            .then(response => {
                this.students.data = response.data
            });
        },
        editStudent (studentId) {
            location.href = `/students/${studentId}`
        },
        exportStudents () {
            $(e.target).button('loading')
            axios.get('/api/students/export')
            .then(response => {
                $(e.target).button('reset')
                location.href = response.data;
            });
        }
    }
}
</script>
