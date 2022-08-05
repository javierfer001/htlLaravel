<template>
  <b-card-code
    title="Data Technician"
  >
    <validation-observer ref="simpleRules">
      <b-form>
        <b-row>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="first_name"
                rules="required"
              >
                <b-form-input
                  v-model="data.first_name"
                  :state="errors.length > 0 ? false:null"
                  placeholder="First Name"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="last_name"
                rules="required"
              >
                <b-form-input
                  v-model="data.last_name"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Last Name"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="truck_number"
                rules="required"
              >
                <b-form-input
                  v-model="data.truck_number"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Truck Number"
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
  BFormInput, BFormGroup, BForm, BRow, BCol, BButton,
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
  },
  data() {
    return {
      data: {
        first_name: '',
        last_name: '',
        truck_number: '',
      },
      positive,
    }
  },
  created() {
    const { id } = this.$route.params
    if (id !== '0') {
      this.$http.get(`/api/technicians/${id}`)
        .then(res => {
          this.data = res.data.data
        })
    }
  },
  methods: {
    validationForm() {
      this.$refs.simpleRules.validate().then(() => {
        const data = {
          first_name: this.data.first_name,
          last_name: this.data.last_name,
          truck_number: this.data.truck_number,
        }
        const { id } = this.$route.params
        if (id === '0') {
          this.$http.post('/api/technicians', data).then(() => {
            this.$router.replace('/technicians').then(() => {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'The technician was created',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
            })
          })
        } else {
          this.$http.put(`/api/technicians/${id}`, data).then(() => {
            this.$router.replace('/technicians').then(() => {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'The technician was updated',
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
