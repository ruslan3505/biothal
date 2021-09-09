
import variables from '@/styles/main.scss'
import {mapActions, mapGetters} from "vuex";


export default {
    data() {
        return {
          isShowFavorite: false,
          api: process.env.VUE_APP_REQUEST_BASE_URL,
          errorValid: {

          },
          variables,
          image_uri: process.env.VUE_APP_REQUEST_BASE_URL + '/storage/img/products/'
        }
    },
    computed: {
        isMobile() {
            return screen.width <= 600
        },
        isAuthorize() {
            return this.$store.getters.getToken;
        },
      ...mapGetters('modals', {
        visible_basket: 'visible_basket',
        visible_basket_info: 'visible_basket_info',
        data_basket_info: 'visible_data_basket_info'
      })
    },
    methods: {
      ...mapActions('modals', {
        action_visible_basket: 'ACTION_VISIBLE_BASKET',
        action_visible_basket_info: 'ACTION_VISIBLE_BASKET_INFO',
        action_data_basket_info: 'ACTION_VISIBLE_DATA_BASKET_INFO'
      }),
        toPage(rout) {
            if (this.$router.history.current.name !== rout.name
                || JSON.stringify(this.$router.history.current.params) !== JSON.stringify(rout.params ? rout.params : {})) {
                this.$router.push({name: rout.name, params: rout.params})
            }
        },
        async logout() {
            const token = this.$store.getters.getToken;
            await this.axios.post('logout',{

            },{
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            this.$store.commit("SET_TOKEN", null);
            this.toPage({name: 'home'})
        },
        errorMessagesValidation(e) {
            let status
            if (e.error) {
                status = e.error?.response?.status ? e.error?.response.status : null
            } else {
                status = e.status ? e.status : null
            }
            if (status === 422) {
                let errors
                if (e.error) {
                    errors = e.error.response.data.errors || {}
                } else {
                    errors = e.data.errors || {}
                }

                Object.keys(errors).forEach((key) => {
                    errors[key] = errors[key][0]
                })
                this.errorValid = this.lodash.merge(this.errorValid, errors)
                this.$notify({
                    type: 'error',
                    title: 'Извините!',
                    text: 'Вы не корректно заполнили поля на сайте, исправьте и попробуйте еще раз'
                });
            } else if (status === 401) {
                return true
            } else {
                let message
                if (e.error) {
                    message = e ? e.error.response?.data?.message || e.error.response?.data?.data : e
                } else {
                    message = e ? e.data.message || e.data.message : e
                }
                this.$notify({
                    type: 'error',
                    title: 'Извините!',
                    text: message
                });
            }
        }
    }
}
