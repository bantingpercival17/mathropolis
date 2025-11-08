<template>
    <div class="intro-bg" :style="{ backgroundImage: `url('/images/${currentView}-bg.png')` }">
        <div id="app" class="game-container container-sm p-4 mx-auto d-flex flex-column align-items-center">

            <!-- Header -->
            <header
                class="w-100 mb-4 d-flex justify-content-between align-items-center p-3 bg-white bg-opacity-90 rounded-4 shadow-lg z-10">
                <h1 class="fs-5 fw-bold text-primary">MATHROPOLIS (Vue)</h1>
                <div class="d-flex align-items-center">
                    <!--   <button @click="toggleMusic" class="btn btn-sm btn-outline-info shadow-sm me-2">
                        {{ isMusicPlaying ? '🔊 Music ON' : '🔇 Music OFF' }}
                    </button> -->
                    <button @click="$router.push('/map')" v-show="currentView !== 'map'"
                        class="btn btn-secondary shadow-sm">
                        &leftarrow; Back to Map
                    </button>
                </div>
            </header>

            <!-- Map View -->
            <div v-if="currentView === 'map'" class="w-100 d-flex flex-column align-items-center">
                <h2 class="fs-3 fw-bolder text-white text-center mb-4 p-3 bg-primary rounded-4 shadow-xl z-10">Financial
                    Quest Selection</h2>

                <!-- Map Container with Absolute Positioning -->
                <div class="map-background-container w-100 position-relative">
                    <div v-for="id in questOrder" :key="id" :data-id="id"
                        :class="['building-overlay', 'shadow-md', { 'locked': !questStatus.unlocked.includes(id) }]"
                        @click="enterBuilding(id)">

                        <span v-if="!questStatus.unlocked.includes(id)" class="lock-icon">🔒</span>
                        <p class="fs-6 fw-bold text-dark mb-0 lh-1">{{ gameData[id].name }}</p>
                        <p class="text-secondary small mb-0 lh-1">({{ gameData[id].concept }})</p>
                        <p class="fw-bold mt-1 text-success small mb-0 lh-1">Score: {{ highScores[id] || 0 }}/{{ id ===
                            'supermarket' ? 1 : gameData[id].problems.length || 5 }}</p>
                    </div>
                </div>

                <!-- Progress Footer -->
                <div class="mt-4 p-3 bg-primary rounded-4 w-100 text-center text-white fw-bold shadow-xl z-10">
                    Financial Quest Progress: <span id="quest-status">{{ questStatusText }}</span>
                </div>
                <p class="text-secondary small mt-2 z-10">Progress saved locally in your browser.</p>
            </div>

            <!-- Shopping View -->
            <div v-else-if="currentView === 'supermarket'" class="w-100">
                <h2 class="fs-3 fw-bolder text-white text-center mb-4 p-3 bg-success rounded-4 shadow-xl">🛒 Supermarket
                    Aisle: Calculate and Add to Cart</h2>

                <div class="row g-4 mb-4">
                    <!-- Products Column -->
                    <div class="col-md-7">
                        <div class="bg-white p-4 rounded-4 shadow-lg h-100">
                            <h4 class="text-success border-bottom pb-2 fw-semibold">Available Products (Click to
                                Calculate & Add)</h4>
                            <div class="row g-2 scrollable-v-60">
                                <div v-for="item in SUPERMARKET_PRODUCTS" :key="item.id" class="col-12 col-sm-6">
                                    <div :class="['item-card', 'p-2', 'border', 'rounded-3', { 'border-danger': item.discount > 0, 'border-success': item.discount === 0 }]"
                                        @click="startItemCalculation(item.id)">
                                        <!-- Image and details -->
                                        <div class="p-2 d-flex align-items-start">
                                            <img :src="item.imageUrl" :alt="item.name" class="me-3 rounded-2 shadow-sm"
                                                style="width: 64px; height: 64px; object-fit: cover; flex-shrink: 0;"
                                                onerror="this.onerror=null;this.src='https://placehold.co/64x64/95a5a6/ffffff?text=Item';">

                                            <div class="d-flex flex-column">
                                                <h6 class="fw-bold mb-1 text-dark">{{ item.name }}</h6>
                                                <p class="text-success fs-5 mb-1 lh-1">
                                                    Price: <span class="fw-bold">₱{{ (item.salePrice ||
                                                        item.basePrice).toFixed(2) }}</span>
                                                </p>
                                                <span
                                                    :class="['sale-tag', 'align-self-start', 'mt-1', 'px-2', 'py-1', 'rounded-3', item.discount > 0 ? 'bg-danger text-white' : 'bg-secondary-subtle text-dark']">{{
                                                        item.tag }}</span>
                                                <small v-if="item.discount > 0"
                                                    class="text-secondary text-decoration-line-through">Orig: ₱{{
                                                        item.basePrice.toFixed(2) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Column -->
                    <div class="col-md-5">
                        <div class="bg-white p-4 rounded-4 shadow-lg h-100">
                            <h4 class="text-primary border-bottom pb-2 fw-semibold">Your Cart ({{ cartItemCount }}
                                items)</h4>
                            <div class="scrollable-v-40 space-y-2 mb-3">
                                <div v-for="cartItem in Object.values(shoppingCart)" :key="cartItem.item.id"
                                    class="d-flex justify-content-between align-items-center p-2 bg-light rounded-3 mb-2">
                                    <div>
                                        <span class="fw-bold">{{ cartItem.quantity }}x {{ cartItem.item.name }}</span>
                                        <br><small class="text-secondary">@ ₱{{ (cartItem.item.salePrice ||
                                            cartItem.item.basePrice).toFixed(2) }}</small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2 fw-semibold fs-5">₱{{ (cartItem.quantity *
                                            (cartItem.item.salePrice || cartItem.item.basePrice)).toFixed(2) }}</span>
                                        <button
                                            class="btn btn-sm btn-outline-danger p-0 rounded-circle text-lg fw-bold lh-1"
                                            @click="removeItemFromCart(cartItem.item.id)"
                                            style="width: 28px; height: 28px;">&minus;</button>
                                    </div>
                                </div>
                                <div v-if="cartItemCount === 0" class="text-center text-secondary p-4">Cart is empty.
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-top border-secondary-subtle">
                                <p class="mb-1 d-flex justify-content-between">Subtotal (before all discounts): <span
                                        class="fw-bold">₱{{ cartSubtotal.toFixed(2) }}</span></p>
                                <p class="mb-1 d-flex justify-content-between text-danger">Total Individual Savings:
                                    <span class="fw-bold">-₱{{ cartSavings.toFixed(2) }}</span>
                                </p>
                                <p class="fs-5 d-flex justify-content-between text-success fw-bolder">Total to Pay
                                    (Current): <span class="fw-bolder">₱{{ cartTotal.toFixed(2) }}</span></p>
                            </div>

                            <button @click="startSupermarketQuiz" :disabled="cartItemCount < 2"
                                :class="['btn', 'btn-block', 'w-100', 'px-4', 'py-3', 'fw-bold', 'mt-3', 'shadow-lg', 'rounded-4', 'transition', cartItemCount >= 2 ? 'btn-success' : 'btn-secondary', cartItemCount < 2 ? 'disabled' : '']">
                                Go to Checkout (Minimum 2 Items)
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz View -->
            <div v-else-if="currentView === 'quiz'" class="w-100">
                <h2 class="fs-3 fw-bolder text-white text-center mb-4 p-3 bg-primary rounded-4 shadow-xl">{{
                    currentQuiz.name }} - {{ gameData[currentQuiz.id].concept }}</h2>

                <div class="bg-white p-5 rounded-4 shadow-lg w-100">
                    <div v-if="currentQuiz.id !== 'supermarket'"
                        class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2 border-secondary-subtle">
                        <p class="fs-5 fw-semibold text-primary mb-0">Question {{ currentQuiz.currentQIndex + 1 }} of {{
                            currentQuiz.questions.length }}</p>
                        <p class="fs-5 fw-semibold text-success mb-0">Current Score: {{ currentQuiz.score }}</p>
                    </div>

                    <div class="bg-light p-4 rounded-3 mb-4 shadow-sm">
                        <p class="fs-5 fw-bold text-dark lh-base" v-html="currentProblemText"></p>
                    </div>

                    <!-- Answer Input -->
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <div class="flex-grow-1">
                            <div class="input-group">
                                <span
                                    class="input-group-text p-3 bg-secondary-subtle fw-bold border-2 border-primary">₱</span>
                                <input type="number" v-model.number="userAnswer" @keydown.enter="checkAnswer"
                                    :disabled="!isAnswerInputEnabled" placeholder="Enter answer (e.g., 400.00)"
                                    class="form-control p-3 border-2 border-primary shadow-sm">
                            </div>
                        </div>

                        <button @click="checkAnswer" :disabled="!isAnswerInputEnabled"
                            :class="['btn', 'px-5', 'py-3', 'fw-bold', 'shadow-lg', 'rounded-4', 'flex-shrink-0', isAnswerInputEnabled ? 'btn-primary' : 'btn-secondary disabled']">
                            SUBMIT
                        </button>
                    </div>

                    <div v-show="showNextButton" class="mt-4 text-center">
                        <button @click="nextQuestion"
                            class="btn btn-warning px-5 py-3 text-white fw-bold rounded-4 shadow-lg">
                            NEXT QUESTION &rarr;
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Quiz Modal -->
        <div class="modal fade" id="itemQuizModal" tabindex="-1" aria-labelledby="itemQuizModalLabel" aria-hidden="true"
            ref="itemQuizModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-2xl">
                    <div class="modal-header bg-success text-white rounded-top-4">
                        <h5 class="fs-5 fw-bold" id="itemQuizModalLabel">🛒 Item Calculation Challenge</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="fs-5 mb-3" v-html="modalProblemText"></p>
                        <div :class="['mb-3', 'fw-bold', 'text-center', modalFeedbackColor]" v-html="modalFeedback">
                        </div>

                        <div class="input-group">
                            <span
                                class="input-group-text p-3 bg-secondary-subtle fw-bold border-2 border-success">₱</span>
                            <input type="number" v-model.number="modalUserAnswer" @keydown.enter="checkItemCalculation"
                                placeholder="Enter total cost"
                                class="form-control p-3 border-2 border-success shadow-sm">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success fw-bold" @click="checkItemCalculation">Confirm &
                            Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Animated Feedback Box -->
        <div id="animated-feedback" :class="['feedback-box', feedbackClass]">{{ feedbackText }}</div>
    </div>


</template>
<script>
import { start } from '@popperjs/core';
import { Modal } from 'bootstrap';
export default {
    name: "BuildingLayout",
    data() {
        return {
            // --- State ---
            currentView: 'map',
            highScores: {},
            questStatus: { unlocked: ['supermarket'] },
            shoppingCart: {},
            userAnswer: null,
            isAnswerInputEnabled: true,
            showNextButton: false,

            // Quiz State
            currentQuiz: {
                id: 'supermarket',
                name: '',
                questions: [],
                currentQIndex: 0,
                score: 0
            },

            // Modal State
            modalInstance: null,
            itemQuizModalInstance: null,
            modalUserAnswer: null,
            modalFeedback: '',
            modalFeedbackColor: 'text-gray-800',
            modalProblemText: '',
            currentItemProblem: null,

            // Global Feedback
            feedbackText: '',
            feedbackClass: '',

            // Constants
            SUPERMARKET_PRODUCTS: [
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
            ],

            gameData: {
                supermarket: { id: 'supermarket', name: 'Supermarket', concept: 'Discounts', icon: '🛒', bg: 'bg-supermarket-quiz', next: 'fastfood', problems: [{ item: 'Full Cart', price: 0, concept: '10% loyalty discount', type: 'final_price_discount' }], isShopping: true },
                fastfood: { id: 'fastfood', name: 'Fast Food Corner', concept: 'Tax/Tip', icon: '🍔', bg: 'bg-fastfood-quiz', next: 'tech', problems: [{ item: 'A large pizza order', price: 750, concept: '12% VAT', type: 'final_price_tax' }, { item: 'A cafe latte', price: 180, concept: '12% VAT', type: 'final_price_tax' }, { item: 'A dinner bill of ₱1200 with a 10% tip', price: 1200, concept: '12% VAT and 10% tip', type: 'final_price_tax_tip' }, { item: 'A dessert platter', price: 420, concept: '12% VAT', type: 'final_price_tax' }, { item: 'A delivery order of ₱900 with a 5% service charge', price: 900, concept: '12% VAT and 5% service charge', type: 'final_price_tax_tip' }] },
                tech: { id: 'tech', name: 'Tech Brokerage', concept: 'Commission', icon: '📈', bg: 'bg-tech-quiz', next: 'bank', problems: [{ item: 'A new gaming PC', price: 60000, concept: '5% commission', type: 'commission_amount' }, { item: 'A premium software license', price: 4000, concept: '15% commission', type: 'commission_amount' }, { item: 'A smartphone sale', price: 35000, concept: '3% commission', type: 'commission_amount' }, { item: 'Website hosting package', price: 1500, concept: '12% commission', type: 'commission_amount' }, { item: 'Cloud storage subscription', price: 800, concept: '20% commission', type: 'commission_amount' }] },
                bank: { id: 'bank', name: 'Mega Bank', concept: 'Simple Interest', icon: '🏦', bg: 'bg-bank-quiz', next: 'review', problems: [{ item: 'A small loan', price: 5000, concept: '9% for 3 years', type: 'interest_amount' }, { item: 'A savings deposit', price: 10000, concept: '4% for 5 years', type: 'interest_amount' }, { item: 'A student loan', price: 20000, concept: '7% for 2 years', type: 'interest_amount' }, { item: 'A short-term bond', price: 8000, concept: '6% for 1 year', type: 'interest_amount' }, { item: 'A time deposit', price: 15000, concept: '5% for 4 years', type: 'interest_amount' }] },
                review: { id: 'review', name: 'Review Hall', concept: 'Mixed Concepts', icon: '🎓', bg: 'bg-review-quiz', next: null, problems: [] }
            },

            questOrder: ['supermarket', 'fastfood', 'tech', 'bank', 'review'],
            REQUIRED_SCORE_TO_UNLOCK: 1,
            LOYALTY_DISCOUNT_RATE: 0.10
        };
    },

    computed: {
        currentBgClass() {
            const id = this.currentQuiz.id;
            return this.currentView === 'map' ? 'bg-city-map' : (this.gameData[id]?.bg || 'bg-gray-100');
        },

        cartItemCount() {
            return Object.values(this.shoppingCart).reduce((sum, item) => sum + item.quantity, 0);
        },

        cartSubtotal() {
            return Object.values(this.shoppingCart).reduce((sum, item) => sum + (item.item.basePrice * item.quantity), 0);
        },

        cartTotal() {
            return Object.values(this.shoppingCart).reduce((sum, item) => sum + ((item.item.salePrice || item.item.basePrice) * item.quantity), 0);
        },

        cartSavings() {
            return this.cartSubtotal - this.cartTotal;
        },

        currentProblem() {
            return this.currentQuiz.questions[this.currentQuiz.currentQIndex];
        },

        questStatusText() {
            let completed = 0;
            this.questOrder.forEach(id => {
                const maxScore = this.gameData[id].problems.length || (id === 'supermarket' ? 1 : 5);
                const score = this.highScores[id] || 0;
                if (score >= maxScore) completed++;
            });
            return `${completed} of ${this.questOrder.length} Quests Completed.`;
        }
    },

    methods: {
        goBack() {
            this.$router.push('/map');
        },

        showMap() {
            this.currentView = 'map';
        },

        shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        },

        showAnimatedFeedback(isCorrect, text = null) {
            this.feedbackText = text || (isCorrect ? 'CORRECT!' : 'TRY AGAIN!');
            this.feedbackClass = (isCorrect ? 'bg-green-600' : 'bg-red-600') + ' show-feedback';
            setTimeout(() => {
                this.feedbackClass = this.feedbackClass.replace(' show-feedback', '');
            }, 1000);
        },

        loadProgress() {
            try {
                const scores = localStorage.getItem('mathropolis_scores');
                this.highScores = scores ? JSON.parse(scores) : {};

                const quest = localStorage.getItem('mathropolis_quest');
                this.questStatus = quest ? JSON.parse(quest) : { unlocked: ['supermarket'] };
            } catch (e) {
                console.error('Error loading progress:', e);
            }
        },

        saveProgress() {
            localStorage.setItem('mathropolis_scores', JSON.stringify(this.highScores));
            localStorage.setItem('mathropolis_quest', JSON.stringify(this.questStatus));
        },

        saveHighScore(id, score) {
            if ((this.highScores[id] || 0) < score) {
                this.highScores[id] = score;
                this.saveProgress();
            }
        },

        unlockNextQuest(id) {
            const idx = this.questOrder.indexOf(id);
            const next = this.questOrder[idx + 1];
            if (next && !this.questStatus.unlocked.includes(next)) {
                this.questStatus.unlocked.push(next);
                this.saveProgress();
                this.showAnimatedFeedback(true, `QUEST COMPLETE! ${this.gameData[next].name} UNLOCKED! 🔓`);
            } else if (!next) {
                this.showAnimatedFeedback(true, `MATHROPOLIS MASTERED! 🏆`);
            }
        },

        enterBuilding(id) {
            const building = this.gameData[id];
            if (!this.questStatus.unlocked.includes(id)) {
                this.showAnimatedFeedback(false, "LOCKED! Achieve max score in the previous area first.");
                return;
            }
            this.currentQuiz.id = id;
            this.userAnswer = null;
            if (building.isShopping) this.enterSupermarket();
            else this.startStandardQuiz(id);
        },
        startItemCalculation(itemId) {
            const item = this.SUPERMARKET_PRODUCTS.find(p => p.id === itemId);
            if (!item) return;

            const quantity = Math.floor(Math.random() * 4) + 2;
            const unitPrice = item.salePrice || item.basePrice;
            const correctTotal = (unitPrice * quantity);

            this.currentItemProblem = {
                itemId: itemId,
                quantity: quantity,
                correctAnswer: Math.round(correctTotal * 100) / 100
            };

            let discountInfo = '';
            if (item.discount > 0) {
                discountInfo = ` (This item is already **${(item.discount * 100).toFixed(0)}% OFF** from its original price of ₱${item.basePrice.toFixed(2)})`;
            }

            this.modalProblemText = `
                        You want to buy **${quantity}x ${item.name}**.<br>
                        The unit price is **₱${unitPrice.toFixed(2)}**. ${discountInfo}<br><br>
                        What is the **TOTAL PRICE** for all **${quantity}** units?
                    `;
            this.modalUserAnswer = null;
            this.modalFeedback = '';
            this.modalFeedbackColor = 'text-dark';
            const modalEl = this.$refs.itemQuizModal;
            this.modalInstance = new Modal(modalEl);
            this.modalInstance.show();
            setTimeout(() => document.querySelector('#itemQuizModal input').focus(), 500);
            console.log('Item clicked:', itemId);
        },
        checkItemCalculation() {

            const userAns = this.modalUserAnswer;
            const correctAns = this.currentItemProblem.correctAnswer;

            if (userAns === null || isNaN(userAns)) {
                this.modalFeedback = 'Please enter a valid number.';
                this.modalFeedbackColor = 'text-danger';
                return;
            }

            if (Math.abs(userAns - correctAns) < 0.01) {
                this.modalFeedback = `Correct! The total price is ₱${correctAns.toFixed(2)}.`;
                this.modalFeedbackColor = 'text-success';
                this.addItemToCart(this.currentItemProblem.itemId);
            } else {
                this.modalFeedback = `Incorrect. The correct total price is ₱${correctAns.toFixed(2)}.`;
                this.modalFeedbackColor = 'text-danger';
            }

        },
        addItemToCart(itemId) {
            const item = this.SUPERMARKET_PRODUCTS.find(p => p.id === itemId);
            if (!item) return;

            if (this.shoppingCart[itemId]) {
                this.shoppingCart[itemId].quantity += this.currentItemProblem.quantity;
            } else {
                this.shoppingCart[itemId] = {
                    item: item,
                    quantity: this.currentItemProblem.quantity
                };
            }
            this.modalInstance.hide();
            this.showAnimatedFeedback(true, `${this.currentItemProblem.quantity}x ${item.name} added to cart! 🛒`);
            this.saveProgress();
            /*  const modalEl = this.$refs.itemQuizModal;
             const modalInstance = new Modal(modalEl);
             modalInstance.hide(); */
        },
        startSupermarketQuiz() {
            if (this.cartItemCount < 2) {
                this.showAnimatedFeedback(false, 'Add at least 2 items to the cart before checkout.');
                return;
            }

            const finalCartTotal = this.cartTotal * (1 - this.LOYALTY_DISCOUNT_RATE);

            this.currentQuiz = {
                id: 'supermarket',
                name: 'Supermarket Checkout Quiz',
                questions: [{
                    item: 'Full Cart',
                    price: finalCartTotal,
                    concept: '10% loyalty discount',
                    type: 'final_price_discount'
                }],
                currentQIndex: 0,
                score: 0
            };

            this.currentView = 'quiz';
            this.userAnswer = null;
            this.isAnswerInputEnabled = true;
            this.showNextButton = false;
        },
        startStandardQuiz(id) {
            const building = this.gameData[id];
            let problems = building.problems.map(p => ({ ...p }));
            problems = this.shuffleArray(problems);

            this.currentQuiz = {
                id: id,
                name: building.name + ' Quiz',
                questions: problems,
                currentQIndex: 0,
                score: 0
            };

            this.currentView = 'quiz';
            this.userAnswer = null;
            this.isAnswerInputEnabled = true;
            this.showNextButton = false;
        },
        nextQuestion() {
            this.currentQuiz.currentQIndex++;
            this.userAnswer = null;
            this.isAnswerInputEnabled = true;
            this.showNextButton = false;

            if (this.currentQuiz.currentQIndex >= this.currentQuiz.questions.length) {
                this.endQuiz();
            }
        },
        endQuiz() {
            const id = this.currentQuiz.id;
            const score = this.currentQuiz.score;
            this.saveHighScore(id, score);

            const maxScore = this.gameData[id].problems.length || (id === 'supermarket' ? 1 : 5);
            if (score >= this.REQUIRED_SCORE_TO_UNLOCK) {
                this.unlockNextQuest(id);
            } else {
                this.showAnimatedFeedback(false, `Quiz ended. You scored ${score}/${maxScore}. Try again to unlock the next area.`);
            }

            this.currentView = 'map';
            this.shoppingCart = {};
            this.saveProgress();
        },

        // All your other methods from setup() go here exactly as written...
        // (checkAnswer, startItemCalculation, checkItemCalculation, addItemToCart,
        // startSupermarketQuiz, startStandardQuiz, nextQuestion, endQuiz, etc.)
    },

    mounted() {
        this.loadProgress();
        this.currentView = this.$route.params.building
    }
};
</script>
<style scoped>
.intro-bg {
    /* 1. Sets the image source */
    /* background-image: url('/images/supermarket-bg.png'); */
    /* 2. Centers the image */
    background-position: center;
    /* 3. SCALES the image to cover the entire container (the key to responsiveness) */
    background-size: cover;
    /* 4. Prevents the image from tiling */
    background-repeat: no-repeat;
    /* This element is the positioning context for the GameMap component */
    position: relative;
}

:root {
    --bs-primary: #1d4ed8;
    /* Blue 700 equivalent */
    --bs-success: #10b981;
    /* Green 500 equivalent */
    --bs-warning: #f59e0b;
    /* Amber 500 equivalent */
    --bs-danger: #ef4444;
    /* Red 500 equivalent */
    --bs-dark: #1f2937;
}

body {
    font-family: 'Inter', sans-serif;
    transition: background-color 0.5s ease;
    overflow-x: hidden;
    overflow-y: auto;
    background-color: #f8f9fa;
    /* Default background */
}

.game-container {
    min-height: 100vh;
    /* No need for d-flex/flex-column here as it's defined in the div */
}

/* --- Custom Background Classes for Immersion --- */
.bg-city-map {
    background-image: url('https://i.imgur.com/e2V5lRj.png');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
}

/* Using Bootstrap color palette for quizzes */
.bg-supermarket-quiz {
    background-image: linear-gradient(to top, #059669 0%, #34d399 100%);
}

.bg-fastfood-quiz {
    background-image: linear-gradient(to top, #f97316 0%, #fcd34d 100%);
}

.bg-tech-quiz {
    background-image: linear-gradient(to top, #1e293b 0%, #4b5563 100%);
}

.bg-bank-quiz {
    background-image: linear-gradient(to top, #9ca3af 0%, #e5e7eb 100%);
}

.bg-review-quiz {
    background-image: linear-gradient(to top, #7c3aed 0%, #c4b5fd 100%);
}

/* Styling for the interactive building overlays on the map */
.building-overlay {
    position: absolute;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 0.75rem;
    padding: 0.5rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out, background-color 0.15s ease-in-out;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: var(--bs-dark);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    min-width: 120px;
    border: 3px solid #ccc;
    /* Added border for definition */
}

.building-overlay:not(.locked):hover {
    transform: scale(1.05);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    background-color: rgba(255, 255, 255, 0.95);
}

.building-overlay.locked {
    filter: grayscale(80%);
    cursor: not-allowed;
    opacity: 0.6;
    box-shadow: none;
}

.building-overlay .lock-icon {
    position: absolute;
    top: 0.25rem;
    right: 0.25rem;
    font-size: 1.25rem;
    line-height: 1.75rem;
}

/* Specific positioning for each building on the map image */
/* Adjusted positioning to better overlay the provided map image */
.building-overlay[data-id="supermarket"] {
    top: 12%;
    left: 65%;
}

.building-overlay[data-id="fastfood"] {
    top: 52%;
    left: 42%;
    transform: translateX(-50%);
}

.building-overlay[data-id="tech"] {
    top: 40%;
    left: 15%;
    transform: translateX(-50%);
}

.building-overlay[data-id="bank"] {
    top: 40%;
    right: 10%;
    transform: translateX(50%);
}

.building-overlay[data-id="review"] {
    top: 12%;
    left: 30%;
    transform: translateX(-50%);
}


.feedback-box {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    padding: 30px 60px;
    border-radius: 15px;
    color: white;
    font-size: 2rem;
    font-weight: bold;
    text-align: center;
    opacity: 0;
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    z-index: 1000;
    box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
}

.show-feedback {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
}

.item-card {
    transition: all 0.2s;
    cursor: pointer;
    height: 100%;
    border-width: 3px !important;
}

.item-card:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    transform: translateY(-2px);
}

.sale-tag {
    font-size: 0.8rem;
    font-weight: bold;
    padding: 0.2rem 0.5rem;
    border-radius: 0.5rem;
}

/* Container for the map background image (Aspect Ratio 16:9) */
.map-background-container {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%;
    /* 16:9 Aspect Ratio */
    background-image: url('https://i.imgur.com/e2V5lRj.png');
    background-size: contain;
    background-position: center top;
    background-repeat: no-repeat;
    border-radius: 1rem;
    overflow: hidden;
}

/* Custom height for scrollable areas in small containers */
.scrollable-v-60 {
    max-height: 60vh;
    overflow-y: auto;
}

.scrollable-v-40 {
    max-height: 40vh;
    overflow-y: auto;
}

.input-group-text {
    border-color: var(--bs-primary);
}
</style>