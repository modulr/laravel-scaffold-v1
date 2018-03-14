<template lang="html">
    <div>
        <qrcode-reader @decode="searchQR" v-if="showQRSearch">
            <div class="qr-scanner-back"></div>
            <div class="qr-scanner"><em></em><span></span></div>
            <p class="qr-error text-danger" v-html="errorQRSearch"></p>
        </qrcode-reader>
    </div>
</template>

<script>
import { QrcodeReader } from 'vue-qrcode-reader'
export default {
    data () {
        return {
            showQRSearch: false,
            errorQRSearch: null,
            autopart: {}
        }
    },
    components: {
        QrcodeReader
    },
    mounted () {
        $('#modalSearchQR').on('show.bs.modal', this.showScanQR)
        $('#modalSearchQR').on('hidden.bs.modal', this.hideScanQR)
    },
    methods: {
        showScanQR () {
            this.errorQRSearch = ''
            this.showQRSearch = true
        },
        hideScanQR () {
            this.errorQRSearch = ''
            this.showQRSearch = false
        },
        searchQR (id) {
            axios.get('/api/autoparts/show/'+id)
            .then(response => {
                if (response.data) {
                    this.autopart = response.data
                    this.$emit('findAutopart', this.autopart)
                    $('#modalSearchQR').modal('hide')
                    $('#modalAutopart').modal('show')
                } else {
                    this.errorQRSearch = `The Autopart <strong>${id}</strong> do not exist!`
                }
            });
        }
    }
}
</script>
