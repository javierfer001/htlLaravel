<template>
  <b-card-code
    title="Data Order"
  >
    <validation-observer ref="simpleRules">
      <b-form>
        <b-row>
          <b-col md="6">
            <b-form-group label="Vehicle">
              <validation-provider
                #default="{ errors }"
                name="vehicle_id"
                rules="required"
              >
                <b-form-select
                  v-model="data.vehicle_id"
                  :options="vehiclesList"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Key">
              <validation-provider
                #default="{ errors }"
                name="key_id"
                rules="required"
              >
                <b-form-select
                  v-model="data.key_id"
                  :options="keysList"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Technician">
              <validation-provider
                #default="{ errors }"
                name="technician_id"
                rules="required"
              >
                <b-form-select
                  v-model="data.technician_id"
                  :options="techniciansList"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group  label="Status">
              <validation-provider
                #default="{ errors }"
                name="status"
                rules="required"
              >
                <b-form-select
                  v-model="data.status"
                  :options="statusList"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group  label="Note">
              <validation-provider
                #default="{ errors }"
                name="note"
                rules="required"
              >
                <b-form-input
                  v-model="data.note"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Notes"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col cols="12">
            <b-button
              variant="primary"
              type="submit"
              @click.prevent="validationForm"
            >
              Submit
            </b-button>
          </b-col>
        </b-row>
      </b-form>
    </validation-observer>
  </b-card-code>
</template>

<script>
import BCardCode from '@core/components/b-card-code'
import { ValidationProvider, ValidationObserver } from 'vee-validate'
import {
  BFormInput, BFormGroup, BForm, BRow, BCol, BButton, BFormSelect,
} from 'bootstrap-vue'
import { positive } from '@validations'
// eslint-disable-next-line import/extensions
import ToastificationContent from '@core/components/toastification/ToastificationContent'

export default {
  components: {
    BCardCode,
    ValidationProvider,
    ValidationObserver,
    BFormInput,
    BFormGroup,
    BForm,
    BRow,
    BCol,
    BButton,
    BFormSelect,
  },
  data() {
    return {
      data: {
        vehicle_id: '',
        key_id: '',
        technician_id: '',
        note: '',
        status: '',
      },
      vehiclesList: [],
      keysList: [],
      techniciansList: [],
      statusList: [
        {
          value: 'pending',
          text: 'Pending',
        },
        {
          value: 'approved',
          text: 'Approved',
        },
        {
          value: 'declined',
          text: 'Declined',
        },
      ],
      positive,
    }
  },
  created() {
    const { id } = this.$route.params
    const request = [
      this.$http.get('/api/vehicles'),
      this.$http.get('/api/keys'),
      this.$http.get('/api/technicians'),
    ]
    if (id !== '0') {
      request.push(this.$http.get(`/api/orders/${id}`))
    }
    Promise.all(request).then(res => {
      this.vehiclesList = res[0].data.data.map(record => ({
        value: record.id,
        text: `${record.year} ${record.make} ${record.model}`,
      }))
      this.keysList = res[1].data.data.map(record => ({
        value: record.id,
        text: `${record.name}`,
      }))
      this.techniciansList = res[2].data.data.map(record => ({
        value: record.id,
        text: `${record.last_name}, ${record.first_name}`,
      }))
      if (id !== '0') {
        this.data = res[3].data.data
      }
    })
  },
  methods: {
    validationForm() {
      this.$refs.simpleRules.validate().then(() => {
        const data = {
          vehicle_id: this.data.vehicle_id,
          key_id: this.data.key_id,
          technician_id: this.data.technician_id,
          note: this.data.note,
          status: this.data.status,
        }
        const { id } = this.$route.params
        if (id === '0') {
          this.$http.post('/api/orders', data).then(() => {
            this.$router.replace('/home').then(() => {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'The order was created',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
            })
          })
        } else {
          this.$http.put(`/api/orders/${id}`, data).then(() => {
            this.$router.replace('/home').then(() => {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'The order was updated',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
            })
          })
        }
      })
    },
  },
}
</script>
