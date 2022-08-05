<template>
  <b-card-code
    title="Data Key"
  >
    <validation-observer ref="simpleRules">
      <b-form>
        <b-row>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="Vehicle ID"
                rules="required"
              >
                <b-form-input
                  v-model="data.vehicle_id"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Vehicle ID"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="name"
                rules="required"
              >
                <b-form-input
                  v-model="data.name"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Key name"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="description"
                rules="required"
              >
                <b-form-input
                  v-model="data.description"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Key Description"
                />
                <small class="text-danger">{{ errors[0] }}</small>
              </validation-provider>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group>
              <validation-provider
                #default="{ errors }"
                name="price"
                rules="required|positive"
              >
                <b-form-input
                  v-model="data.price"
                  :type="positive"
                  :state="errors.length > 0 ? false:null"
                  placeholder="Key Price"
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
        vehicle_id: '',
        name: '',
        description: '',
        price: '',
      },
      positive,
    }
  },
  created() {
    console.log(this.$route.params.id)
    const { id } = this.$route.params
    if (id !== '0') {
      this.$http.get(`/api/keys/${id}`)
        .then(res => {
          console.log(res.data.data)
          this.data = res.data.data
        })
    }
  },
  methods: {
    validationForm() {
      this.$refs.simpleRules.validate().then(success => {
        const data = {
          vehicle_id: this.data.vehicle_id,
          name: this.data.name,
          description: this.data.description,
          price: this.data.price,
        }
        const { id } = this.$route.params
        if (id === '0') {
          this.$http.post('/api/keys', data).then(res => {
            this.$router.replace('/keys').then(() => {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'The key was created',
                  icon: 'CoffeeIcon',
                  variant: 'success',
                },
              })
            })
          })
        } else {
          console.log(data)
          this.$http.put(`/api/keys/${id}`, data).then(res => {
            this.$router.replace('/keys').then(() => {
              this.$toast({
                component: ToastificationContent,
                position: 'top-right',
                props: {
                  title: 'The key was created',
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
