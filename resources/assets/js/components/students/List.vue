<template lang="html">
    <div class="students">
        <div class="container-fluid">
            <!-- Actionbar -->
            <div class="actionbar">
                <div class="row">
                    <div class="col-md-12 controls text-right">
                        <a href="#" class="btn btn-default">
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
                                    <th>ID</th>
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
                                <tr v-for="item in students.data">
                                    <td>{{item.id}}</td>
                                    <td>{{item.name}}</td>
                                    <td>{{item.last_name}}</td>
                                    <td>{{item.gender.name}}</td>
                                    <td>{{item.birthday}}</td>
                                    <td>{{item.study.name}}</td>
                                    <td>{{item.position.name}}</td>
                                    <td>{{item.years_in_position}}</td>
                                    <td>{{item.payment_method.name}}</td>
                                    <td>{{item.certificate.name}}</td>
                                    <td>
                                        <span v-if="item.have_studied">Si</span>
                                        <span v-else>No</span>
                                    </td>
                                    <td>{{item.username}}</td>
                                    <td>{{item.phone}}</td>
                                    <td>{{item.cellphone}}</td>
                                    <td>{{item.email}}</td>
                                    <td>{{item.store.name}}</td>
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
                // paginate info
                // ...
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
        }
    }
}
</script>
