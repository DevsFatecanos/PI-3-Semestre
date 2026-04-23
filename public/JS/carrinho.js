document.documentElement.classList.add('js');

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
            document.querySelector('input[name="_token"]')?.value;

        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        if (csrfToken) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        }

        document.addEventListener('DOMContentLoaded', function() {
            initReveals();
            initSwiper();
            initCatalogFilters();
            updateCartBadge();
        });

        function initReveals() {
            const blocks = document.querySelectorAll('.reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                    }
                });
            }, { threshold: 0.15 });

            blocks.forEach((block) => observer.observe(block));
        }

        function initSwiper() {
            const counterCurrent = document.getElementById('offerCounterCurrent');
            const counterRoot = document.getElementById('offerCounter');
            const total = parseInt(counterRoot?.dataset.total || '1', 10);

            const swiper = new Swiper('.mySwiper', {
                slidesPerView: 1.15,
                spaceBetween: 18,
                loop: true,
                speed: 850,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 18 },
                    1024: { slidesPerView: 3, spaceBetween: 22 },
                },
            });

            function updateOfferCounter() {
                if (!counterCurrent) return;
                const current = swiper.realIndex + 1;
                const pad = (num) => String(Math.max(1, num)).padStart(2, '0');
                counterCurrent.textContent = pad(current);
                const totalEl = document.getElementById('offerCounterTotal');
                if (totalEl) {
                    totalEl.textContent = pad(total);
                }
            }

            updateOfferCounter();
            swiper.on('slideChange', updateOfferCounter);

            if (swiper.el) {
                swiper.el.addEventListener('mouseenter', () => swiper.autoplay.stop());
                swiper.el.addEventListener('mouseleave', () => swiper.autoplay.start());
            }
        }

        function initCatalogFilters() {
            const searchInput = document.getElementById('productSearch');
            const categoryFilter = document.getElementById('categoryFilter');
            const minPriceFilter = document.getElementById('minPriceFilter');
            const maxPriceFilter = document.getElementById('maxPriceFilter');
            const clearBtn = document.getElementById('clearFilters');
            const chips = Array.from(document.querySelectorAll('[data-category-chip]'));
            const sections = Array.from(document.querySelectorAll('[data-category-section]'));
            const globalEmptyState = document.getElementById('globalEmptyState');

            const VISIBLE_LIMIT = 6;
            const sectionPages = new Map();

            chips.forEach((chip) => {
                chip.addEventListener('click', () => {
                    const slug = chip.dataset.categoryChip;
                    if (categoryFilter.value === slug) {
                        categoryFilter.value = '';
                    } else {
                        categoryFilter.value = slug;
                    }
                    resetPages();
                    applyFilters();
                });
            });

            sections.forEach((section) => {
                const sectionSlug = section.dataset.categorySection;
                const nextButton = section.querySelector('[data-show-more]');
                const prevButton = section.querySelector('[data-prev-lot]');
                if (nextButton) {
                    nextButton.addEventListener('click', () => {
                        const nextPage = (sectionPages.get(sectionSlug) || 0) + 1;
                        sectionPages.set(sectionSlug, nextPage);
                        animateSection(section);
                        window.setTimeout(applyFilters, 140);
                    });
                }

                if (prevButton) {
                    prevButton.addEventListener('click', () => {
                        const currentPage = sectionPages.get(sectionSlug) || 0;
                        const prevPage = Math.max(0, currentPage - 1);
                        sectionPages.set(sectionSlug, prevPage);
                        animateSection(section);
                        window.setTimeout(applyFilters, 140);
                    });
                }
            });

            [searchInput, categoryFilter, minPriceFilter, maxPriceFilter].forEach((el) => {
                el?.addEventListener('input', () => {
                    resetPages();
                    applyFilters();
                });
                el?.addEventListener('change', () => {
                    resetPages();
                    applyFilters();
                });
            });

            clearBtn?.addEventListener('click', () => {
                searchInput.value = '';
                categoryFilter.value = '';
                minPriceFilter.value = '';
                maxPriceFilter.value = '';
                sectionPages.clear();
                applyFilters();
            });

            function resetPages() {
                sectionPages.clear();
            }

            function animateSection(section) {
                const grid = section.querySelector('[data-category-grid]');
                if (!grid) return;

                grid.classList.add('is-switching');
                window.setTimeout(() => grid.classList.remove('is-switching'), 220);
            }

            function normalize(value) {
                return (value || '').toString().trim().toLowerCase();
            }

            function applyFilters() {
                const nameTerm = normalize(searchInput?.value);
                const selectedCategory = categoryFilter?.value || '';
                const minPrice = parseFloat(minPriceFilter?.value || '');
                const maxPrice = parseFloat(maxPriceFilter?.value || '');
                const hasMinPrice = !Number.isNaN(minPrice);
                const hasMaxPrice = !Number.isNaN(maxPrice);

                let totalVisible = 0;

                chips.forEach((chip) => {
                    chip.classList.toggle('active', chip.dataset.categoryChip === selectedCategory && selectedCategory !== '');
                });

                sections.forEach((section) => {
                    const sectionSlug = section.dataset.categorySection;
                    const cards = Array.from(section.querySelectorAll('[data-product-card]'));
                    const emptyCategory = section.querySelector('[data-empty-category]');
                    const visibleCountLabel = section.querySelector('[data-visible-count]');
                    const showMoreBtn = section.querySelector('[data-show-more]');
                    const prevBtn = section.querySelector('[data-prev-lot]');
                    const batchLabel = section.querySelector('[data-batch-label]');
                    const grid = section.querySelector('[data-category-grid]');

                    const matchingCards = cards.filter((card) => {
                        const productName = normalize(card.dataset.productName);
                        const productCategory = card.dataset.productCategory;
                        const productPrice = parseFloat(card.dataset.productPrice || '0');

                        const nameMatch = !nameTerm || productName.includes(nameTerm);
                        const categoryMatch = !selectedCategory || selectedCategory === productCategory;
                        const minPriceMatch = !hasMinPrice || productPrice >= minPrice;
                        const maxPriceMatch = !hasMaxPrice || productPrice <= maxPrice;

                        return nameMatch && categoryMatch && minPriceMatch && maxPriceMatch;
                    });

                    const filtersActive = Boolean(nameTerm || selectedCategory || hasMinPrice || hasMaxPrice);
                    const totalPages = Math.max(1, Math.ceil(matchingCards.length / VISIBLE_LIMIT));
                    const maxPageIndex = Math.max(0, totalPages - 1);
                    const requestedPage = sectionPages.get(sectionSlug) || 0;
                    const page = Math.min(requestedPage, maxPageIndex);

                    if (requestedPage !== page) {
                        sectionPages.set(sectionSlug, page);
                    }

                    const startIndex = page * VISIBLE_LIMIT;
                    const endIndex = startIndex + VISIBLE_LIMIT;
                    const currentBatch = matchingCards.length === 0 ? 0 : page + 1;

                    cards.forEach((card) => {
                        card.classList.add('hidden');
                    });

                    const visibleBatch = matchingCards.slice(startIndex, endIndex);

                    visibleBatch.forEach((card) => {
                        card.classList.remove('hidden');
                    });

                    const visibleInSection = visibleBatch.length;
                    totalVisible += visibleInSection;

                    if (visibleCountLabel) {
                        visibleCountLabel.textContent = visibleInSection.toString();
                    }

                    if (batchLabel) {
                        batchLabel.textContent = `${String(currentBatch || 1).padStart(2, '0')}/${String(totalPages).padStart(2, '0')}`;
                    }

                    if (emptyCategory) {
                        emptyCategory.classList.toggle('hidden', matchingCards.length > 0);
                    }

                    if (showMoreBtn) {
                        const hasMore = endIndex < matchingCards.length;
                        const showButton = hasMore && matchingCards.length > VISIBLE_LIMIT;
                        showMoreBtn.classList.toggle('hidden', !showButton);
                        if (showButton) {
                            showMoreBtn.textContent = filtersActive ? 'Próximos resultados' : 'Próximo lote';
                        }
                    }

                    if (prevBtn) {
                        const showPrev = page > 0;
                        prevBtn.classList.toggle('hidden', !showPrev);
                        if (showPrev) {
                            prevBtn.textContent = 'Lote anterior';
                        }
                    }

                    section.classList.toggle('hidden', filtersActive && matchingCards.length === 0);
                });

                if (globalEmptyState) {
                    globalEmptyState.classList.toggle('hidden', totalVisible > 0);
                }
            }

            applyFilters();
        }

        function openCartModal() {
            loadCart();
            document.getElementById('cartModal').classList.remove('hidden');
            document.getElementById('cartModal').classList.add('flex');
        }

        function closeCartModal() {
            document.getElementById('cartModal').classList.add('hidden');
            document.getElementById('cartModal').classList.remove('flex');
        }

        function addToCart(event) {
            event.preventDefault();

            const form = event.currentTarget || event.target;
            const quantidadeInput = form.querySelector('input[name="quantidade"]');
            const quantidade = quantidadeInput ? quantidadeInput.value : 1;
            const url = form.getAttribute('action');

            if (!url) {
                showNotification('Erro ao adicionar ao carrinho', 'error');
                return false;
            }

            axios.post(url, { quantidade }, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => {
                if (response.data.success) {
                    showNotification(response.data.message, 'success');
                    updateCartBadge();
                    if (!document.getElementById('cartModal').classList.contains('hidden')) {
                        loadCart();
                    }
                }
            })
            .catch(error => {
                const message = error.response?.data?.message
                    || error.response?.data?.errors?.quantidade?.[0]
                    || 'Erro ao adicionar ao carrinho';
                showNotification(message, 'error');
            });

            return false;
        }

        function loadCart() {
            axios.get('/api/carrinho')
                .then(response => {
                    renderCart(response.data);
                })
                .catch(() => {
                    document.getElementById('cartContent').innerHTML = '<p class="text-center text-red-500">Erro ao carregar carrinho</p>';
                });
        }

        function renderCart(data) {
            const { carrinho, total } = data;
            const cartContent = document.getElementById('cartContent');

            if (carrinho.length === 0) {
                cartContent.innerHTML = '<p class="py-8 text-center text-slate-500">Carrinho vazio</p>';
                document.getElementById('cartTotal').textContent = 'R$ 0,00';
                return;
            }

            let html = '<div class="space-y-4">';

            carrinho.forEach(item => {
                const produto = item.produto;
                html += `
                    <div class="flex items-start justify-between rounded-lg border p-3 hover:bg-slate-50">
                        <div class="flex-1">
                            <p class="text-sm font-black">${produto.nome}</p>
                            <p class="text-xs text-slate-500">${produto.marca ?? ''}</p>
                            <p class="mt-1 text-sm font-bold">R$ ${formatPrice(produto.preco_atual)}</p>
                            <div class="mt-2 flex items-center gap-2">
                                <button onclick="updateQuantity(${produto.id}, ${item.quantidade - 1})" class="rounded bg-slate-200 px-2 py-1 text-xs hover:bg-slate-300">-</button>
                                <span class="px-2 text-sm font-bold">${item.quantidade}</span>
                                <button onclick="updateQuantity(${produto.id}, ${item.quantidade + 1})" class="rounded bg-slate-200 px-2 py-1 text-xs hover:bg-slate-300">+</button>
                            </div>
                        </div>
                        <div class="ml-2 text-right">
                            <p class="text-sm font-black">R$ ${formatPrice(item.subtotal)}</p>
                            <button onclick="removeFromCart(${produto.id})" class="mt-2 text-xs text-red-500 hover:text-red-700">Remover</button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            cartContent.innerHTML = html;
            document.getElementById('cartTotal').textContent = `R$ ${formatPrice(total)}`;
        }

        function updateQuantity(productId, newQuantidade) {
            if (newQuantidade < 1) {
                removeFromCart(productId);
                return;
            }

            axios.put(`/carrinho/${productId}`, { quantidade: newQuantidade }, {
                headers: { 'Accept': 'application/json' }
            })
            .then(() => {
                loadCart();
                updateCartBadge();
            })
            .catch(() => {
                showNotification('Erro ao atualizar quantidade', 'error');
            });
        }

        function removeFromCart(productId) {
            axios.delete(`/carrinho/${productId}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => {
                if (response.data && response.data.carrinho) {
                    renderCart(response.data);
                    updateCartBadgeValue(response.data.quantidadeTotal || 0);
                } else {
                    loadCart();
                    updateCartBadge();
                }
                showNotification(response.data?.message || 'Produto removido do carrinho', 'success');
            })
            .catch(() => {
                showNotification('Erro ao remover produto', 'error');
            });
        }

        function updateCartBadgeValue(quantidade) {
            const badge = document.getElementById('carrinhoCountBadge');
            if (!badge) return;

            if (quantidade > 0) {
                badge.textContent = quantidade;
                badge.classList.remove('hidden');
                badge.classList.add('flex');
            } else {
                badge.classList.remove('flex');
                badge.classList.add('hidden');
            }
        }

        function updateCartBadge() {
            axios.get('/api/carrinho').then(response => {
                updateCartBadgeValue(response.data.quantidadeTotal || 0);
            });
        }

        function formatPrice(price) {
            return parseFloat(price).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed right-4 top-4 z-50 rounded-lg border px-4 py-3 text-sm font-bold ${
                type === 'success'
                    ? 'border-emerald-200 bg-emerald-100 text-emerald-700'
                    : 'border-red-200 bg-red-100 text-red-700'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => notification.remove(), 3000);
        }

        function finalizarPedido() {
            window.location.href = '/checkout';
        }

        document.getElementById('cartModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCartModal();
            }
        });