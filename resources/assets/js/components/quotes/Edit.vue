<template>
    <div class="modal right fade" id="modalEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit quote</h4>
                </div>
                <div class="modal-body">
                  <form class="form-horizontal" action="index.html" method="post">
                    <div class="form-group">
                        <label class="col-sm-3 control-label required">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-lg" placeholder="Name" required v-model="quote.name">
                        </div>
                    </div>
                    <label class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-9">
                        <textarea name="description" class="form-control input-lg" placeholder="Description" required v-model="quote.description">
                        </textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label required">Customer</label>
                        <div class="col-sm-9">
                            <div class="col-sm-9">
                              <p class="form-control-static" v-if="quote.customer">
                                <small class="text-mute">{{quote.customer.name}}</small>
                              </p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label required">Project</label>
                        <div class="col-sm-9">
                          <p class="form-control-static" v-if="quote.project">
                            <small class="text-mute">{{quote.project.name}}</small>
                          </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label required">Designer</label>
                        <div class="col-sm-9">
                            <multiselect v-model="quote.designer" label="name" :options="list.designers" placeholder="Designer"></multiselect>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label required">Salesman</label>
                        <div class="col-sm-9">
                            <multiselect v-model="quote.salesman" label="name" :options="list.sellers" placeholder="Salesman"></multiselect>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label required">Service</label>
                        <div class="col-sm-9">
                            <multiselect v-model="quote.service" label="title" :options="list.services" placeholder="Service"></multiselect>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Amount </label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control input-lg" placeholder="Amount" required v-model="quote.amount">
                        </div>
                        <label class="col-sm-3 control-label">Currency </label>
                        <div class="col-sm-9">
                          <select type="text" class="form-control input-lg" placeholder="Currency" required v-model="quote.currency_id">
                            <option v-for="currency in list.currencies" :value="currency.id"> {{ currency.title }} </option>
                          </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Request Date </label>
                        <div class="col-sm-9">
                          <p class="form-control-static">
                            <small class="text-mute">{{quote.request_date | date}}</small>
                          </p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Delivery Date </label>
                        <div class='col-sm-9 date'>
                            <input type='date' class="form-control" v-model="quote.delivery_date"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" @click="destroy()">
                        <i class="mdi mdi-delete mdi-lg"></i> Delete
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="update">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment';
import swal from 'sweetalert';
import Vue2Filters from 'vue2-filters';
import Multiselect from 'vue-multiselect'
export default {
  props: ['list', 'quotes', 'quote'], 
  components: {
        Multiselect
    },
    data() {
        return {
            error: {}            
        }
    },
    filters: {
      date (date) {
          return date ? moment(date).format('LL'): '';
      },
      datetime (date) {
          return date ? moment(date).format('LLL'): '';
      }
  },
  methods : {
      update (e) {
      var btn = $(e.target).button('loading')
      this.quote.customer_id = this.quote.customer.id
      this.quote.designer_id = this.quote.designer.id
      this.quote.salesman_id = this.quote.salesman.id
      this.quote.service_id = this.quote.service.id
      axios.put('/quote/update/'+this.quote.id, this.quote)
        .then(response => {
          this.$parent.quotes[this.quote.index] = response.data
          this.$parent.quote = {}
          this.error = {}
          var btn = $(e.target).button('reset')
          $('#modalEdit').modal('hide')
        })
        .catch(error => {
          this.error = error.response.data;
          var btn = $(e.target).button('reset')
        });
    },
    destroy () {
      var self = this;
      swal({
        title: "Are you sure?",
        text: "You will not be able to recover this quote!",
        type: "warning",
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
      },
      function(){
        axios.delete('/quote/destroy/' + self.quote.id)
        .then(response => {
          self.$parent.quotes.splice(self.quote.index, 1);
          swal({
              title: "Deleted!",
              text: "The quote has been deleted.",
              type: "success",
              timer: 1000,
              showConfirmButton: false
          });
          self.error = {};
          $('#modalEdit').modal('hide')
        })
        .catch(error => {
            self.error = error.response.data
        });
      });
    },
  }
}
</script>
