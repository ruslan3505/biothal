<template>
    <v-app>
        <MenuDesktop v-if="!isMobile"/>
        <MenuMobile v-if="isMobile"/>
        <v-main app>
            <router-view/>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-57NZVR4"
                    height="0" width="0" style="display:none;visibility:hidden"/>
        </v-main>
        <FooterDesktop v-if="!isMobile"/>
        <FooterMobile v-if="isMobile"/>
    </v-app>
</template>

<script>
    import MenuDesktop from "../components/desktop/MenuDesktop";
    import FooterDesktop from "../components/desktop/FooterDesktop";
    import MenuMobile from "../components/mobile/MenuMobile";
    import FooterMobile from "../components/mobile/FooterMobile";

    export default {
        name: "Dashboard",
        components: {
            MenuDesktop,
            FooterDesktop,
            MenuMobile,
            FooterMobile
        },
        mounted() {
            let recaptchaScript = document.createElement('script')
            recaptchaScript.setAttribute('src', this.api + '/js/widget.js')
            document.head.appendChild(recaptchaScript)
        },
        created() {
            (function(d, w, s) {
                var widgetHash = 'mr4swiw83bp19edxoqs5', gcw = d.createElement(s); gcw.type = 'text/javascript'; gcw.async = true;
                gcw.src = '//widgets.binotel.com/getcall/widgets/'+ widgetHash +'.js';
                var sn = d.getElementsByTagName(s)[0]; sn.parentNode.insertBefore(gcw, sn);
            })(document, window, 'script');

            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-57NZVR4');
        }
    }
</script>

<style scoped lang="scss">
    .v-main {
        padding-top: 0 !important;
    }
</style>
