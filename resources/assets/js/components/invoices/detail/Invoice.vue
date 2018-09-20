<template lang="html">
  <div class="invoice">
    <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
    <div class="invoice-content">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{invoiceClone.description}}</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4">
              <dl>
                <dd>Description</dd>
                <dt>{{invoiceClone.description}}</dt>
              </dl>
              <dl>
                <dd>Created At</dd>
                <dt>{{invoiceClone.created_at | date}}</dt>
              </dl>
            </div>
            <div class="col-xs-4">
              <dl>
                <dd>Owner</dd>
                <dt v-if="invoiceClone.owner">
                  <a :href="'/profile/'+invoiceClone.owner.id">{{invoiceClone.owner.name}}</a>
                </dt>
              </dl>
              <dl>
                <dd>File</dd>
                <dt>
                  <a :href="invoiceClone.url" target="_blank">Inspect File</a>
                </dt>
              </dl>
            </div>
            <div class="col-xs-4">
              <dl>
                <dd>Amount</dd>
                <dt>{{invoiceClone.amount | currency}}</dt>
              </dl>
              <dl v-if="invoiceClone.invoice_status">
                <dd>Status</dd>
                <dt>{{invoiceClone.invoice_status.name}}</dt>
              </dl>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-8">
              <a href="#" class="btn btn-primary pull-right" @click.prevent="changeStatus(1)">
                  <i class="mdi mdi-check mdi-lg"></i> Mark as In progress
              </a>
            </div>
            <div class="col-sm-2">
              <a href="#" class="btn btn-primary pull-right" @click.prevent="changeStatus(3)">
                  <i class="mdi mdi-thumb-up mdi-lg"></i> Mark as Chargued
              </a>
            </div>
            <div class="col-sm-2">
              <a href="#" class="btn btn-primary pull-right" @click.prevent="changeStatus(2)">
                  <i class="mdi mdi-thumb-down mdi-lg"></i> Mark as Canceled
              </a>
            </div>                          
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <h3>Quotes</h3>
          <table class="table table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Project</th>
                    <th>Designer</th>
                    <th>Salesman</th>
                    <th>Amount</th>
                    <th>Invoiced</th>
                    <th>Currency</th>
                    <th>Request Date</th>
                    <th>Delivery Date</th>
                    <th>Status</th>                    
                </tr>
            </thead>
            <tbody>
                <tr v-for="(quote, index) in invoice.quotes">
                    <td> {{ quote.id }} </td>
                    <td> {{ quote.name }} </td>
                    <td> {{ quote.project.name }} </td>
                    <td> {{ quote.designer | displayData('name', 'Diseñador eliminado') }} </td>
                    <td> {{ quote.salesman | displayData('name', 'Vendedor eliminado') }} </td>
                    <td> {{ quote.amount | currency }} </td>
                    <td> {{ quote.pivot.amount | currency }} </td>
                    <td> {{ quote.currency.title }} </td>
                    <td> {{ quote.request_date | date }} </td>
                    <td> {{ quote.delivery_date | date }} </td>
                    <td>
                        <span class="chip" :class="'status-'+quote.status.title">
                            {{quote.status.title}}
                        </span>
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
  import Vue2Filters from 'vue2-filters';
  export default {
    data() {
      return {
        loading: false,
        error: {},
        invoiceClone: {}
      }
    },
    props: ['invoice'],
    components: {
      Spinner
    },
    mounted() {
      this.invoiceClone = _.clone(this.invoice)
    },
    filters: {
      date(date) {
        return moment(date).format('LL');
      },
      displayData (obj, key, string) {
          if (!obj) {
              return string;
          }
          if (obj[key]) {
              return obj[key];
          }
          return string;
      }
    },
    methods: {
      changeStatus(status) {
        this.invoiceClone.invoice_status_id = status        
        var self = this;
          swal({
                title: "Are you sure?",
                text: "You will do this action",
                type: "warning",
                showLoaderOnConfirm: true,
                showCancelButton: true,
                confirmButtonText: "Yes, do it!",
                closeOnConfirm: true
            },
            function () {
                axios.put('/invoices/update/' + self.invoice.id, self.invoiceClone)
                  .then(response => {
                      self.invoiceClone = response.data                      
                  })
                  .catch(error => {
                      self.error = error.response.data;
                  });
            });
      },
      totalInvoices (quote) {
        let total = 0.00
        quote.invoices.forEach(item => {
            total += parseFloat(item.pivot.amount)
        })
        quote.invoiced = total
        quote.remainder = quote.amount - quote.invoiced
        return total
      }
    }
  }
</script>