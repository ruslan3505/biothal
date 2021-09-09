import global from "../mixins/global";

const isMobile = global.computed.isMobile()
const isShowFavorite = global.data().isShowFavorite

const routes = [
    {
        path: '',
        name: 'dashboard',
        redirect: {name: 'home'},
        component: () => import('../pages/Dashboard'),
        meta: {
            label: 'Главная страница'
        },
        children: [
            {
                path: 'home',
                name: 'home',
                component: () => import('../pages/Home')
            },
            {
                path: 'home/invite/:token',
                name: 'invite',
                component: () => import('../pages/Home'),
                props: true
            },
            {
                path: 'checkout',
                name: 'checkout',
                component: () => import('../pages/Checkout'),
                meta: {}
            },
            {
                path: 'account-settings',
                name: 'account-settings',
                component: () => {
                    return isMobile ? import('../pages/mobile/AccountSettingsMobile') : import('../pages/desktop/AccountSettingsDesktop')
                },
                meta: {}
            },
            {
                path: 'password-edit',
                name: 'password-edit',
                component: () => import('../pages/mobile/PasswordEditMobile'),
                meta: {}
            },
            {
                path: 'order-list',
                name: 'order-list',
                component: () => {
                    return isMobile ? import('../pages/mobile/OrderListMobile') : import('../pages/mobile/OrderListMobile')
                },
                meta: {}
            },
            {
                path: 'group-discount-participants',
                name: 'group-discount-participants',
                component: () => {
                    return isMobile ? import('../pages/mobile/GroupDiscountParticipantsMobile') : import('../pages/mobile/GroupDiscountParticipantsMobile')
                },
                meta: {}
            },
            {
                path: 'bank-cards',
                name: 'bank-cards',
                component: () => {
                    return isMobile ? import('../pages/mobile/BankCardsMobile') : import('../pages/mobile/BankCardsMobile')
                },
                meta: {}
            },
            {
                path: 'favorites',
                name: 'favorites',
                redirect: isShowFavorite ? false : '/home',
                component: () => import('../pages/Favorites'),
                meta: {}
            },
            {
                path: 'product/:id',
                name: 'product',
                component: () => {
                    return isMobile ? import('../pages/mobile/ProductMobile') : import('../pages/desktop/ProductDesktop')
                },
                meta: {},
                props: true
            },
            {
                path: 'ordering',
                name: 'ordering',
                component: () => {
                    return isMobile ? import('../pages/mobile/OrderingMobile') : import('../pages/desktop/OrderingDesktop')
                },
                meta: {},
                props: true
            },
            {
                path: 'category/:category/:subCategory?',
                name: 'category',
                component: () => import('../pages/CategoryPage'),
                meta: {},
                props: true
            },
            {
                path: 'category/new_products',
                name: 'new_category',
                component: () => import('../pages/CategoryPage'),
                meta: {},
                props: true
            },
            {
                path: 'accessory/:accessory',
                name: 'accessories',
                component: () => import('../pages/CategoryPage'),
                meta: {},
                props: true
            },
            {
                path: 'info-page/:id',
                name: 'info-page',
                component: () => import('../pages/desktop/InfoPage'),
                meta: {}
            },
            {
                path: 'authorization',
                name: 'authorization',
                component: () => import('../pages/mobile/AuthorizationMobile'),
                meta: {}
            },
            {
                path: 'distributor',
                name: 'distributor',
                component: () => import('../pages/DistributorPage'),
                meta: {}
            },
            {
                path: 'registration',
                name: 'registration',
                component: () => import('../pages/mobile/RegistrationMobile'),
                meta: {}
            },
            {
                path: 'registration/invite/:token',
                name: 'invite_in_registration',
                component: () => import('../pages/mobile/RegistrationMobile'),
                props: true
            },
            {
                path: 'Verified',
                name: 'Verified',
                component: () => import('../pages/mobile/Verified'),
                meta: {},
                props: true
            },
            {
                path: 'order-status/:token',
                name: 'order-status',
                component: () => import('../pages/desktop/OrderingStatusDesktop'),
                meta: {},
                props: true
            },
            {
                path: 'order-cancel/:token',
                name: 'order-cancel',
                component: () => import('../pages/desktop/OrderingCancelDesktop'),
                meta: {},
                props: true
            },
            {
                path: 'payment',
                name: 'payment',
                component: () => {
                    return isMobile ? import('../pages/mobile/PaymentMobile') : import('../pages/desktop/PaymentDesktop')
                },
                props: true,
                meta: {}
            }
        ]
    },
    {
        path: '/404',
        name: '404',
        component: () => import('../pages/NotFound'),
        meta: {}
    },
    {
        path: '*',
        redirect: '/404',
        meta: {}
    }
]

export default routes
