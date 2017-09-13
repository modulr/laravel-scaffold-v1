<template>
    <div class="customers">
        <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
        <div class="wrapper" v-if="!loading">
            <div class="row">
                <div class="col-xs-12 text-right">
                    <a href="#" class="btn btn-primary">
                        <i class="mdi mdi-person-add mdi-lg"></i> Add Customer
                    </a>
                </div>
            </div>
            <br>
            <!-- No items found -->
            <div class="row" v-if="customers.length == 0">
                <div class="col-md-12">
                    <h3>No customers found.</h3>
                </div>
            </div>
            <!-- List -->
            <div class="row" v-if="customers.length != 0">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in customers">
                                <td>
                                    {{item.id}}
                                </td>
                                <td>
                                    {{item.name}}<br>
                                </td>
                                <td class="text-right" @click="edit(item, index)">
                                    <a href="#" class="btn btn-link" @click.prevent="edit(item, index)"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import swal from 'sweetalert';
import Spinner from 'vue-simple-spinner';
export default {
    data() {
      return {
          loading: false,
          customers: [],
          customer: {},
          error: {}
      }
    },
    components: {
      Spinner
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
            this.loading = true;
            axios.get('/customers/all')
                .then(response => {
                    this.customers = response.data;
                    this.loading = false;
                });
        },
    }
}
</script>
