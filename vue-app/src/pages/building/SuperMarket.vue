<template>
    <div class="w-full">
        <h2 class="text-2xl font-extrabold text-white text-center mb-4 p-3 bg-green-600 rounded-xl shadow-xl">🛒
            Supermarket Aisle: Calculate and Add to Cart</h2>

        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <!-- Item List (Left Column) -->
            <div class="md:w-7/12">
                <div class="bg-white p-4 rounded-xl shadow-lg h-full">
                    <h4 class="text-green-600 border-b pb-2 font-semibold">Available Products (Click to Calculate &
                        Add)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 overflow-y-auto max-h-[60vh]">
                        <div v-for="item in SUPERMARKET_PRODUCTS" :key="item.id" class="col-span-1">
                            <div :class="['item-card', 'h-full', 'p-2', 'border-3', 'rounded-lg', { 'border-red-500': item.discount > 0, 'border-green-500': item.discount === 0 }]"
                                @click="startItemCalculation(item.id)">
                                <div class="p-2 flex flex-col">
                                    <h6 class="font-bold mb-1 text-gray-800">{{ item.name }}</h6>
                                    <p class="text-green-600 text-xl">
                                        Price: <span class="font-bold">₱{{ (item.salePrice ||
                                            item.basePrice).toFixed(2) }}</span>
                                    </p>
                                    <span
                                        :class="['sale-tag', 'self-start', 'mt-1', 'px-2', 'py-1', 'text-xs', 'font-bold', 'rounded-md', item.discount > 0 ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-600']">{{
                                            item.tag }}</span>
                                    <small v-if="item.discount > 0" class="text-gray-400 line-through">Orig: ₱{{
                                        item.basePrice.toFixed(2) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary (Right Column) -->
            <div class="md:w-5/12">
                <div class="bg-white p-4 rounded-xl shadow-lg h-full">
                    <h4 class="text-blue-600 border-b pb-2 font-semibold">Your Cart ({{ cartItemCount }} items)</h4>
                    <div class="overflow-y-auto max-h-[40vh] space-y-2 mb-3">
                        <div v-for="cartItem in Object.values(shoppingCart)" :key="cartItem.item.id"
                            class="flex justify-between items-center p-2 bg-gray-50 rounded-lg">
                            <div>
                                <span class="font-bold">{{ cartItem.quantity }}x {{ cartItem.item.name }}</span>
                                <br><small class="text-gray-500">@ ₱{{ (cartItem.item.salePrice ||
                                    cartItem.item.basePrice).toFixed(2) }}</small>
                            </div>
                            <div class="flex items-center">
                                <span class="mr-2 font-semibold text-lg">₱{{ (cartItem.quantity *
                                    (cartItem.item.salePrice || cartItem.item.basePrice)).toFixed(2) }}</span>
                                <button
                                    class="text-red-500 hover:text-red-700 font-bold p-1 rounded-full text-xl leading-none"
                                    @click="removeItemFromCart(cartItem.item.id)">&minus;</button>
                            </div>
                        </div>
                        <div v-if="cartItemCount === 0" class="text-center text-gray-500 p-4">Cart is empty.</div>
                    </div>

                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <p class="mb-1 flex justify-between">Subtotal (before all discounts): <span
                                class="font-bold">₱{{ cartSubtotal.toFixed(2) }}</span></p>
                        <p class="mb-1 flex justify-between text-red-500">Total Individual Savings: <span
                                class="font-bold">-₱{{ cartSavings.toFixed(2) }}</span></p>
                        <p class="text-xl flex justify-between text-green-600 font-extrabold">Total to Pay
                            (Current): <span class="font-extrabold">₱{{ cartTotal.toFixed(2) }}</span></p>
                    </div>

                    <button @click="startSupermarketQuiz" :disabled="cartItemCount < 2"
                        :class="['w-full', 'px-6', 'py-3', 'font-bold', 'mt-3', 'shadow-lg', 'rounded-xl', 'transition', { 'bg-green-600 text-white hover:bg-green-700': cartItemCount >= 2, 'bg-gray-300 text-gray-500 cursor-not-allowed': cartItemCount < 2 }]">
                        Go to Checkout (Minimum 2 Items)
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "SuperMarket",
    data() {
        const SUPERMARKET_PRODUCTS = [
            { id: 1, name: 'Chips', basePrice: 20.00, tag: 'Regular', discount: 0 },
            { id: 2, name: 'Instant Noodles', basePrice: 25.00, tag: 'Regular', discount: 0 },
            { id: 3, name: 'Canned Tuna', basePrice: 35.00, tag: 'Regular', discount: 0 },
            { id: 4, name: 'Chocolate Bar', basePrice: 40.00, tag: 'Regular', discount: 0 },
            { id: 5, name: 'Toothpaste', basePrice: 50.00, tag: 'Regular', discount: 0 },
            { id: 6, name: 'Shampoo Sachets (pack)', basePrice: 60.00, tag: 'Regular', discount: 0 },
            { id: 7, name: 'Bread Loaf', basePrice: 70.00, tag: 'Regular', discount: 0 },
            { id: 8, name: 'Soap Bar (3 pcs)', basePrice: 80.00, tag: 'Regular', discount: 0 },
            { id: 9, name: 'Eggs (1 dozen)', basePrice: 90.00, tag: 'Regular', discount: 0 },
            { id: 10, name: 'Milk (1 liter)', basePrice: 100.00, tag: 'Regular', discount: 0 },
            { id: 11, name: 'Cooking Oil', basePrice: 120.00, tag: 'Regular', discount: 0 },
            { id: 12, name: 'Rice (2 kg)', basePrice: 150.00, tag: 'Regular', discount: 0 },
            { id: 13, name: 'Detergent Powder', basePrice: 180.00, tag: 'Regular', discount: 0 },
            { id: 14, name: 'Cereal Box', basePrice: 200.00, tag: 'Regular', discount: 0 },
            { id: 15, name: 'Juice Pack (6 pcs)', basePrice: 220.00, tag: 'Regular', discount: 0 },
            { id: 16, name: 'Coffee Jar', basePrice: 250.00, tag: 'Regular', discount: 0 },
            { id: 17, name: 'Sugar (3 kg)', basePrice: 270.00, tag: 'Regular', discount: 0 },
            { id: 18, name: 'Corned Beef (4 cans)', basePrice: 300.00, tag: 'Regular', discount: 0 },
            { id: 19, name: 'Tissue Rolls (10 pcs)', basePrice: 350.00, tag: 'Regular', discount: 0 },
            { id: 20, name: 'Diapers (medium pack)', basePrice: 400.00, tag: 'Regular', discount: 0 },
            { id: 21, name: 'Laundry Soap (bundle)', basePrice: 450.00, tag: 'Regular', discount: 0 },
            { id: 22, name: 'Baby Formula', basePrice: 500.00, tag: 'Regular', discount: 0 },
            { id: 23, name: 'Cheese (block)', basePrice: 100.00, salePrice: 90.00, tag: '10% off', discount: 0.10 },
            { id: 24, name: 'Butter (250g)', basePrice: 100.00, salePrice: 85.00, tag: '15% off', discount: 0.15 },
            { id: 25, name: 'Whole Chicken', basePrice: 300.00, salePrice: 240.00, tag: '20% off', discount: 0.20 },
            { id: 26, name: 'Softdrinks (case)', basePrice: 450.67, salePrice: 338.00, tag: '25% off', discount: 0.25 },
            { id: 27, name: 'Snack Combo Pack', basePrice: 200.00, salePrice: 190.00, tag: '5% off', discount: 0.05 }
        ];

        return {
            SUPERMARKET_PRODUCTS,
            shoppingCart: {},
            cartSubtotal: 0,
            cartSavings: 0,

        }
    },
    methods: {
        startSupermarketQuiz() {
            const currentTotal = cartTotal.value;

            // Setup quiz state for the single final question
            currentQuiz.id = 'supermarket';
            currentQuiz.name = 'Supermarket Checkout';
            currentQuiz.score = 0;
            currentQuiz.currentQIndex = 0;

            currentQuiz.questions = [{
                item: 'Full Cart',
                price: currentTotal,
                concept: `${(LOYALTY_DISCOUNT_RATE * 100)}% loyalty discount`,
                type: 'final_price_discount'
            }];

            currentView.value = 'quiz';
            isAnswerInputEnabled.value = true;
            showNextButton.value = false;
            userAnswer.value = null;
        }
    }
}
</script>