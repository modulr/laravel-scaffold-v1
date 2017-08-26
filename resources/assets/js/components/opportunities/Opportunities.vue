<template lang="html">
    <div class="opportunities">
        <!-- Actions Buttons -->
        <div class="row">
            <div class="col-xs-12 text-right">
                <a href="#" class="btn btn-primary" @click.prevent="add">
                    <i class="mdi mdi-person-add mdi-lg"></i> Add Opportunity
                </a>
            </div>
        </div>
        <br>
        <!-- List -->
        <div class="row">
            <div class="col-md-12">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Start Date</th>
                            <!-- <th></th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in opportunities">
                            <td @click="edit(item, index)">
                                {{item.id}}
                            </td>
                            <td @click="edit(item, index)">
                                {{item.name}}<br>
                            </td>
                            <td @click="edit(item, index)">
                                {{item.start_date | date}}<br>
                            </td>
                            <!-- <td class="text-right" @click="edit(item, index)">
                                <a href="#" class="btn btn-link" @click.prevent="edit(item, index)"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
                            </td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Create opportunity -->
        <div class="modal fade" id="modalAdd">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add Opportunity</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal">
                            <div class="form-group" :class="{'has-error': error.name}">
                                <label class="col-sm-2 control-label">Name *</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control input-lg" placeholder="Name" required v-model="opportunity.name">
                                    <span class="help-block" v-if="error.name">{{error.name[0]}}</span>
                                </div>
                            </div>
                            <div class="form-group" :class="{'has-error': error.start_date}">
                                <label class="col-sm-2 control-label">Start Date *</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" placeholder="Start Date YYYY-MM-DD hh:mm:ss" required v-model="opportunity.start_date">
                                    <span class="help-block" v-if="error.start_date">{{error.start_date[0]}}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" @click="store">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
import swal from 'sweetalert';
export default {
    data() {
        return {
            opportunities: [],
            opportunity: {},
            search: '',
            error: {},
            headers: { 'X-CSRF-TOKEN': Laravel.csrfToken },
            }
    },
    mounted() {
        this.getAll();
    },
    filters: {
        date(date) {
            return moment(date).format('LLL');
        }
    },
    methods: {
        getAll: function() {
            axios.get('/opportunities/all')
                .then(response => {
                    this.opportunities = response.data;
                });
        },
        add: function() {
            this.opportunity = {
                name: '',
                start_date: '',
            };
            this.error = {};
            $('#modalAdd').modal('show');
        },
        store: function(e) {
            var btn = $(e.target).button('loading')
            axios.post('/opportunities/store', this.opportunity)
                .then(response => {
                    this.opportunities.push(response.data);
                    this.opportunity = {};
                    this.error = {};
                    var btn = $(e.target).button('reset')
                    $('#modalAdd').modal('hide');
                })
                .catch(error => {
                    this.error = error.response.data;
                    var btn = $(e.target).button('reset')
                });
        },
    }
}
</script>