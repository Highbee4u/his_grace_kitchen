import './bootstrap';

import Alpine from 'alpinejs';

Alpine.store('currency', {
    selected: localStorage.getItem('nk_currency') || 'NGN',
    rates: {
        NGN: 1,
        GBP: 0.00055,
        USD: 0.00067,
        CAD: 0.00091,
        EUR: 0.00062
    },
    symbols: {
        NGN: '₦',
        GBP: '£',
        USD: '$',
        CAD: 'CA$',
        EUR: '€'
    },
    setCurrency(curr) {
        this.selected = curr;
        localStorage.setItem('nk_currency', curr);
        window.dispatchEvent(new CustomEvent('currency-changed', { detail: { currency: curr } }));
    },
    format(minorAmount) {
        if (minorAmount === null || minorAmount === undefined || isNaN(minorAmount)) {
            return '';
        }
        const curr = this.selected || 'NGN';
        const rate = this.rates[curr] || 1;
        const symbol = this.symbols[curr] || '₦';

        const convertedMajor = (Number(minorAmount) / 100) * rate;

        return symbol + Number(convertedMajor).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
});

Alpine.store('cart', {
    items: JSON.parse(localStorage.getItem('nk_cart') || '[]'),
    isOpen: false,

    addItem(item) {
        const variant = item.variant || null;
        const existingIndex = this.items.findIndex(i => i.id === item.id && i.variant === variant);
        if (existingIndex > -1) {
            this.isOpen = true;
            return false;
        }

        this.items.push({
            id: item.id,
            name: item.name,
            price: Number(item.price),
            currency: item.currency || 'NGN',
            image: item.image,
            variant,
            addOns: item.addOns || [],
            quantity: item.quantity || 1
        });
        this.save();
        this.isOpen = true;
        return true;
    },

    isInCart(id, variant = null) {
        return this.items.some(i => i.id === id && i.variant === (variant || null));
    },

    hasItem(id) {
        return this.items.some(item => item.id === id);
    },

    removeItem(index) {
        this.items.splice(index, 1);
        this.save();
    },

    changeQty(index, delta) {
        this.items[index].quantity += delta;
        if (this.items[index].quantity <= 0) {
            this.removeItem(index);
        } else {
            this.save();
        }
    },

    clear() {
        this.items = [];
        this.save();
    },

    save() {
        localStorage.setItem('nk_cart', JSON.stringify(this.items));
    },

    get count() {
        return this.items.reduce((sum, item) => sum + item.quantity, 0);
    },

    get subtotal() {
        return this.items.reduce((sum, item) => sum + (Number(item.price) * item.quantity), 0);
    },

    get subtotalMinor() {
        return this.subtotal;
    },

    get formattedSubtotal() {
        return Alpine.store('currency').format(this.subtotal);
    }
});

Alpine.start();
