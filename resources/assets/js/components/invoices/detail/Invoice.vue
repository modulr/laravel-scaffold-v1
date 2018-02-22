<template lang="html">
  <div class="invoice">
    <vue-simple-spinner line-fg-color="#FEAE3B" size="big" v-if="loading"></vue-simple-spinner>
    <div class="invoice-content">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{{invoice.name}}</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4">
              <dl>
                <dd>Description</dd>
                <dt>{{invoice.description}}</dt>
              </dl>
              <dl>
                <dd>Created At</dd>
                <dt>{{invoice.created_at | date}}</dt>
              </dl>
            </div>
            <div class="col-xs-4">
              <dl>
                <dd>Owner</dd>
                <dt v-if="invoice.owner">
                  <a :href="'/profile/'+invoice.owner.id">{{invoice.owner.name}}</a>
                </dt>
              </dl>
            </div>
            <div class="col-xs-4">
              <dl>
                <dd>Amount</dd>
                <dt>{{invoice.amount}}</dt>
              </dl>
            </div>
          </div>
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
        error: {}
      }
    },
    props: ['invoice'],
    components: {
      Spinner
    },
    mounted() {

    },
    filters: {
      date(date) {
        return moment(date).format('LL');
      }
    },
    methods: {

    }
  }
</script>