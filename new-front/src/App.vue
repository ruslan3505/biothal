<template>

  <!--    <video autoplay muted loop id="myVideo" v-if="maxWidth > 1200">-->
  <!--      <source src="https://media.istockphoto.com/videos/nature-undersea-very-green-posidonia-field-of-the-mediterranean-sea-video-id1164785970" type="video/mp4">-->
  <!--    </video>-->

    <div id="app">
      <router-view/>
      <notifications/>
      <basket-pre-info class="pre-info" />
    </div>

</template>

<script>

import BasketPreInfo from "@/components/BasketPreInfo";
export default {
  name: 'App',
  components: {BasketPreInfo},
  created() {
    this.checkUser()
  },
  computed: {
    maxWidth() {
      return window.innerWidth;
    }
  },
  methods: {
    async checkUser() {
      try {
        const token = this.$store.getters.getToken;
        if (token) {
          let data = await this.axios.post('checkUser', {}, {
            headers: {
              'Authorization': `Bearer ${token}`
            }
          });
          if (data) {
            let exist = data.data.exist
            if (!exist) {
              await this.$store.dispatch('LOGIN', null);
              return false;
            }
          } else {
            await this.$store.dispatch('LOGIN', null);
          }
          return true;
        } else {
          return false;
        }
      } catch (e) {
        await this.$store.dispatch('LOGIN', null);
        this.errorMessagesValidation(e);
      }
    }
  }
}
</script>

<style lang="scss">

@import "src/styles/mixins";
@import 'src/styles/main';


body {
  //background: url("../public/background.jpg") center no-repeat fixed;
  //background-size: 100vw 100vh;


}

#app {
  //@include _600 {
  //  background-color: $palette-main-background-color;
  //}

  z-index: 1000;

  & main > * > * > * {
    padding: 0 calc((100vw - #{$basic-styles-screen-width}) / 2 - 20px);

    @include _1200 {
      padding: initial;
    }
  }
}

.base-page-wrapper {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.default-cursor {
  cursor: default;
}

div::-webkit-scrollbar-track {
  color: #EAEAEA;
  border-radius: 10px;
}

div::-webkit-scrollbar {
  width: 7px;

  @include _600 {
    display: none;
  }
}

div::-webkit-scrollbar-thumb {
  border-radius: 10px;
  background-color: #EAEAEA;
}

.pre-info {
  top: rem(40);

  @include _600 {
    top: rem(26);
  }
}
</style>
