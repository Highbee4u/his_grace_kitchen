import './bootstrap';

import Alpine from 'alpinejs';

Alpine.store('cart', {
    items: JSON.parse(localStorage.getItem('nk_cart') || '[]'),
    isOpen: false,

    addItem(item) {
        const existingIndex = this.items.findIndex(i => i.id === item.id && i.variant === item.variant);
        if (existingIndex > -1) {
            this.items[existingIndex].quantity += (item.quantity || 1);
        } else {
            this.items.push({
                id: item.id,
                name: item.name,
                price: item.price,
                currency: item.currency || 'NGN',
                image: item.image,
                variant: item.variant || null,
                quantity: item.quantity || 1
            });
        }
        this.save();
        this.isOpen = true;
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
        return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },

    get formattedSubtotal() {
        return '₦' + (this.subtotal / 100).toLocaleString('en-US', { minimumFractionDigits: 2 });
    }
});

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
    }
});

Alpine.start();
