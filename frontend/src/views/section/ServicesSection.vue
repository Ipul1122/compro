<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
    <section id="our-services" class="services-section font-montserrat">

        <!-- Grid Background -->
        <div class="grid-bg" aria-hidden="true"></div>

        <div class="services-container">

            <!-- Header — split layout -->
            <div class="services-header">
                <div class="header-left">
                    <span class="badge">{{ $t('services_section.capabilities') }}</span>
                    <h2 class="services-title">
                        {{ $t('services_section.title') }}
                    </h2>
                </div>
                <div class="header-right">
                    <p class="services-desc">{{ $t('services_section.desc') }}</p>
                    <RouterLink
                        :to="$i18n.locale === 'id' ? '/id/layanan' : '/en/services'"
                        class="cta-btn"
                    >
                        <span>{{ $t('services_section.btn_view_all') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </RouterLink>
                </div>
            </div>

            <!-- Divider line -->
            <div class="divider"></div>

            <!-- Services Grid -->
            <div class="services-grid">

                <!-- Card 01 -->
                <div class="service-card card-featured">
                    <div class="card-number">01</div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $t('services_section.item1.title') }}</h3>
                        <p class="card-desc">
                            {{ $t('services_section.item1.desc1') }}
                            <strong class="highlight">{{ $t('services_section.item1.highlight') }}</strong>.
                        </p>
                    </div>
                    <div class="card-accent" aria-hidden="true"></div>
                </div>

                <!-- Card 02 -->
                <div class="service-card">
                    <div class="card-number">02</div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $t('services_section.item2.title') }}</h3>
                        <ul class="card-list">
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item2.list1_title') }}</strong>
                                    <p>{{ $t('services_section.item2.list1_desc') }}</p>
                                </div>
                            </li>
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item2.list2_title') }}</strong>
                                    <p>{{ $t('services_section.item2.list2_desc') }}</p>
                                </div>
                            </li>
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item2.list3_title') }}</strong>
                                    <p>{{ $t('services_section.item2.list3_desc') }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Card 03 -->
                <div class="service-card">
                    <div class="card-number">03</div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $t('services_section.item3.title') }}</h3>
                        <ul class="card-list">
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item3.list1_title') }}</strong>
                                    <p>{{ $t('services_section.item3.list1_desc') }}</p>
                                </div>
                            </li>
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item3.list2_title') }}</strong>
                                    <p>{{ $t('services_section.item3.list2_desc') }}</p>
                                </div>
                            </li>
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item3.list3_title') }}</strong>
                                    <p>{{ $t('services_section.item3.list3_desc') }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Card 04 -->
                <div class="service-card">
                    <div class="card-number">04</div>
                    <div class="card-body">
                        <h3 class="card-title">{{ $t('services_section.item4.title') }}</h3>
                        <ul class="card-list">
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item4.list1_title') }}</strong>
                                    <p>{{ $t('services_section.item4.list1_desc') }}</p>
                                </div>
                            </li>
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item4.list2_title') }}</strong>
                                    <p>{{ $t('services_section.item4.list2_desc') }}</p>
                                </div>
                            </li>
                            <li>
                                <span class="list-dot"></span>
                                <div>
                                    <strong>{{ $t('services_section.item4.list3_title') }}</strong>
                                    <p>{{ $t('services_section.item4.list3_desc') }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<style scoped>
/* ── Base Section ── */
.services-section {
    position: relative;
    background: #fff;
    padding: 7rem 0 6rem;
    overflow: hidden;
    font-family: 'Montserrat', sans-serif;
}

/* ── Grid Background ── */
.grid-bg {
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(to right, #f1f5f9 1px, transparent 1px),
        linear-gradient(to bottom, #f1f5f9 1px, transparent 1px);
    background-size: 3.5rem 3.5rem;
    -webkit-mask-image: radial-gradient(ellipse 90% 70% at 50% 40%, #000 50%, transparent 100%);
    mask-image: radial-gradient(ellipse 90% 70% at 50% 40%, #000 50%, transparent 100%);
    pointer-events: none;
    z-index: 0;
}

/* ── Container ── */
.services-container {
    position: relative;
    z-index: 1;
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* ── Header Split Layout ── */
.services-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: end;
    margin-bottom: 3rem;
}

.header-left {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.badge {
    display: inline-block;
    width: fit-content;
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #dc2626;
    background: #fef2f2;
    border: 1px solid #fecaca;
    padding: 0.35rem 0.85rem;
    border-radius: 999px;
}

.services-title {
    font-size: clamp(2.4rem, 4.5vw, 3.8rem);
    font-weight: 900;
    color: #0f172a;
    line-height: 1.05;
    letter-spacing: -0.025em;
    text-transform: uppercase;
    margin: 0;
}

.header-right {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    padding-bottom: 0.25rem;
}

.services-desc {
    font-size: 1rem;
    color: #64748b;
    line-height: 1.75;
    font-weight: 500;
    margin: 0;
    max-width: 480px;
}

/* ── CTA Button ── */
.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    background: #0f172a;
    color: #fff;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    text-decoration: none;
    padding: 0.85rem 1.75rem;
    border-radius: 999px;
    width: fit-content;
    transition: background 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
}

.cta-btn:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(220, 38, 38, 0.25);
}

.cta-btn svg {
    transition: transform 0.25s ease;
}

.cta-btn:hover svg {
    transform: translateX(4px);
}

/* ── Divider ── */
.divider {
    height: 1px;
    background: linear-gradient(to right, transparent, #e2e8f0 20%, #e2e8f0 80%, transparent);
    margin-bottom: 3rem;
}

/* ── Services Grid ── */
.services-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    border: 1px solid #e2e8f0;
    border-radius: 1.5rem;
    overflow: hidden;
}

/* ── Service Card ── */
.service-card {
    position: relative;
    padding: 2.25rem 2rem;
    background: #fff;
    border-right: 1px solid #e2e8f0;
    overflow: hidden;
    transition: background 0.3s ease;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.service-card:last-child {
    border-right: none;
}

.service-card:hover {
    background: #fafafa;
}

/* Featured card — first one gets orange accent treatment */
.card-featured {
    background: #0f172a;
}

.card-featured:hover {
    background: #1e293b;
}

.card-featured .card-number {
    color: rgba(255, 255, 255, 0.1);
}

.card-featured .card-title {
    color: #fff;
}

.card-featured .card-desc {
    color: #94a3b8;
}

.card-featured .highlight {
    color: #f87171;
}

.card-accent {
    position: absolute;
    bottom: -1.5rem;
    right: -1.5rem;
    width: 6rem;
    height: 6rem;
    background: #dc2626;
    border-radius: 50%;
    opacity: 0.15;
    transition: transform 0.4s ease, opacity 0.4s ease;
}

.card-featured:hover .card-accent {
    transform: scale(1.4);
    opacity: 0.2;
}

/* ── Card Number ── */
.card-number {
    font-size: 3rem;
    font-weight: 900;
    color: #f1f5f9;
    line-height: 1;
    letter-spacing: -0.03em;
    font-variant-numeric: tabular-nums;
    transition: color 0.3s ease;
}

.service-card:hover .card-number {
    color: #fee2e2;
}

.service-card:hover:not(.card-featured) .card-number {
    color: #fecaca;
}

/* ── Card Body ── */
.card-body {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    flex: 1;
}

.card-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: 0.01em;
    text-transform: uppercase;
    line-height: 1.3;
    margin: 0;
}

.card-desc {
    font-size: 0.82rem;
    color: #64748b;
    line-height: 1.7;
    margin: 0;
}

.highlight {
    color: #dc2626;
    font-weight: 700;
}

/* ── Card List ── */
.card-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.card-list li {
    display: flex;
    gap: 0.65rem;
    align-items: flex-start;
}

.list-dot {
    flex-shrink: 0;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #dc2626;
    margin-top: 0.45rem;
}

.card-list strong {
    display: block;
    font-size: 0.78rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: 0.01em;
    margin-bottom: 0.2rem;
}

.card-list p {
    font-size: 0.76rem;
    color: #94a3b8;
    line-height: 1.6;
    margin: 0;
}

/* ── Responsive ── */
@media (max-width: 1024px) {
    .services-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .service-card:nth-child(2) {
        border-right: none;
    }

    .service-card:nth-child(1),
    .service-card:nth-child(2) {
        border-bottom: 1px solid #e2e8f0;
    }
}

@media (max-width: 768px) {
    .services-section {
        padding: 4rem 0;
    }

    .services-header {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .services-grid {
        grid-template-columns: 1fr;
        border-radius: 1rem;
    }

    .service-card {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
    }

    .service-card:last-child {
        border-bottom: none;
    }
}
</style>