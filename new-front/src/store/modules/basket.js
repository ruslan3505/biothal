const state = {
    unfinishedOrderId: '',
    products: [],
    productData: [],
    globalSales: [],
    groupSales: [],
}

const mutations = {
    ADD_PRODUCT(state, product) {
        const existProduct = state.productData.find(p => p.id === product.id);

        if (existProduct !== undefined) {
            state.productData = state.productData.map(p => {
                p.quantity += +product.quantity;
                return p;
            })
        } else {
            state.productData.push({'id' : product.id , 'quantity' : product.quantity});
        }
    },
    DELETE_PRODUCT(state, productId) {
        state.productData = state.productData.filter(p => p.id !== productId)
        state.products = state.products.filter(p => p.id !== productId)
    },
    CLEAR_ALL_CART(state){
        state.products = []
    },
    CLEAR_UNFINISHED_ORDER_ID(state){
        state.unfinishedOrderId = ''
    },
    INCREMENT_PRODUCT_QUANTITY(state, productId) {
        state.productData = state.productData.map(p => {
            if (p.id === productId) {
                p.quantity++;
            }
            return p;
        })
        state.products.map(el => {
            el.quantity = state.productData.filter(e => e.id === el.id)[0]?.quantity
        })
    },
    DECREMENT_PRODUCT_QUANTITY(state, productId) {
        state.productData = state.productData.map(p => {
            if (p.id === productId) {
                p.quantity--;
            }
            return p;
        })
        state.products.map(el => {
            el.quantity = state.productData.filter(e => e.id === el.id)[0]?.quantity
        })
    },

    SET_PRODUCTS(state, products) {
        products.map(el => {
            el.quantity = state.productData.filter(e => e.id === el.id)[0]?.quantity
        })
        console.log(products)
        state.products = products;


    },
    SET_GLOBAL_SALES(state, globalSales) {
        state.globalSales = globalSales;
    },
    SET_GROUP_SALES(state, groupSales) {
        state.groupSales = groupSales;
    },
    VISIBLE_BASKET(state, visible) {
        state.visible = visible;
    },
    SET_UNFINISHED_ORDER_ID(state, id) {
        state.unfinishedOrderId = id;
    }
}

const actions = {
    SET_PRODUCTS(context, product) {
        context.commit('SET_PRODUCTS', product);
    },    
    ADD_PRODUCT(context, product) {
        context.commit('ADD_PRODUCT', product);
    },
    DELETE_PRODUCT(context, productId) {
        context.commit('DELETE_PRODUCT', productId);
    },
    INCREMENT_PRODUCT_QUANTITY(context, productId) {
        context.commit('INCREMENT_PRODUCT_QUANTITY', productId);
    },
    DECREMENT_PRODUCT_QUANTITY(context, productId) {
        context.commit('DECREMENT_PRODUCT_QUANTITY', productId);
    },
    SET_GLOBAL_SALES(context, globalSales) {
        context.commit('SET_GLOBAL_SALES', globalSales);
    },
    SET_GROUP_SALES(context, groupSales) {
        context.commit('SET_GROUP_SALES', groupSales);
    },
    SET_UNFINISHED_ORDER_ID(context, id) {
        context.commit('SET_UNFINISHED_ORDER_ID', id);
    },
    CLEAR_ALL_CART(context){
        context.commit('CLEAR_ALL_CART');
    },
    CLEAR_UNFINISHED_ORDER_ID(context){
        context.commit('CLEAR_UNFINISHED_ORDER_ID');
    }
}

const getters = {
    products: state => state.products,
    productData: state => state.productData,
    globalSales: state => state.globalSales,
    groupSales: state => state.groupSales,
    visible: state => state.visible,
    unfinishedOrderId: state => state.unfinishedOrderId,

    currentGlobalSales: (state, getters) => {
        let current = null;

        for (const [key, sales] of Object.entries(state.globalSales)) {
            if (sales.sum_modal <= getters.productsSum) {
                current = sales;
            }
        }
        return current;
    },
    currentGroupSales: (state, getters) => {
        let current = null;

        for (const [key, sales] of Object.entries(state.groupSales)) {
            if (sales.sum <= getters.productsSum) {
                current = sales;
            }
        }
        return current;
    },
    nextGlobalSales: (state, getters) => {
        let next = null;

        for (let sales of state.globalSales) {
            if (sales.sum_modal > getters.productsSum) {
                next = sales;
                break;
            }
        }
        return next;
    },
    nextGroupSales: (state, getters) => {
        let next = null;

        for (let sales of state.groupSales) {
            if (sales.sum > getters.productsSum) {
                next = sales;
                break;
            }
        }
        return next;
    },
    linear: (state, getters) => {
        let percentage = 0;

        if (getters.nextGlobalSales !== null) {
            const number = getters.nextGlobalSales.sum_modal / 100;

            if (getters.productsSum > number) {
                percentage = getters.productsSum / number;
            }
        } else {
            if (getters.nextGroupSales !== null) {
                const number = getters.nextGroupSales.sum / 100;

                if (getters.productsSum > number) {
                    percentage = getters.productsSum / number;
                }
            }
        }
        return Math.round(percentage);
    },
    productsSum: state => {
        let sum = 0;
        for (let product of state.products) {
            if (product.sale_id !== null) {
                sum += +product.price_with_sale * +product.quantity;
            } else {
                sum += +product.price * +product.quantity;
            }
        }
        return Math.ceil(sum);
    },
    productsSumWithSales: (state, getters) => {
        let sum = getters.productsSum;

        if (getters.currentGlobalSales !== null) {
            sum = sum - ((sum / 100) * getters.currentGlobalSales.procent_modal);
        } else {
            if (getters.currentGroupSales !== null) {
                sum = sum - ((sum / 100) * getters.currentGroupSales.percent);
            }
        }
        return Math.ceil(sum);
    },
    visibleBasket: (state, getters) => {
        return state.visible;
    },
    getUnfinishedOrderId: state => {
        return state.unfinishedOrderId;
    }
}

export default {
    namespaced: true,
    state,
    mutations,
    getters,
    actions
}
