<template>
    <div class="pagination">
        <div v-if="total" class="number">
            <span>{{ 'Showing %s of %s entries' | lang([from + ' - ' + to, total]) }}</span>
        </div>
        <nav v-if="show">
            <ul>
                <li :class="[{ 'disabled': isFirst }]">
                    <a href="" @click.prevent="load(page-1)">
                        <svg>
                            <use xlink:href="#leftUI" />
                        </svg>
                    </a>
                </li>
                <li v-for="(page, index) in pages" :key="index" :class="[{ 'item': true, 'active': isActive(page), 'disabled': page.disabled }]">
                    <a href="" @click.prevent="load(page.value, page.disabled)" v-text="page.value"></a>
                </li>
                <li :class="[{ 'disabled': isLast }]">
                    <a href="" @click.prevent="load(page+1)">
                        <svg>
                            <use xlink:href="#rightUI" />
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
import { paginate } from './helpers'
export default {
    name: 'GPagination',
    props: {
        total: {
            type: Number,
            required: true
        },
        perPage: {
            type: Number,
            required: true
        },
        page: {
            type: Number,
            required: true
        }
    },
    computed: {
        pages() {
            return paginate(this.page, this.totalP)
        },
        totalP() {
            return Math.ceil(this.total / (this.perPage || 1))
        },
        isFirst() {
            return this.page === 1
        },
        isLast() {
            return this.page >= this.totalP
        },
        show() {
            return this.perPage < this.total
        },
        from() {
            return (this.page - 1) * this.perPage + 1
        },
        to() {
            let x = this.page * this.perPage
            return this.total < x ? this.total : x
        }
    },
    methods: {
        isActive(page) {
            return !page.disabled && this.page === page.value
        },
        load(page, disabled) {
            if(disabled || page < 1 || page > this.totalP) {
                return
            }
            this.$emit('update:page', page)
        }
    }
}
</script>
<style lang="scss">
:root {
    --pagination-number: var(--dark-4);
    --pagination-number-hover: var(--dark-5);
    --pagination-number-active: var(--dark-6);
}
.pagination {
    display: flex;
    justify-content: space-between;
    margin: 20px 0 0 0;
    .number {
        color: var(--text-muted);
        font-size: 14px;
        line-height: 24px;
    }
    nav {
        ul {
            display: flex;
            align-items: center;
            li {
                a {
                    display: block;
                }
                &.item {
                    margin: 0 4px;
                    a {
                        line-height: 20px;
                        font-size: 11px;
                        color: var(--text);
                        font-weight: 500;
                        width: 20px;
                        text-align: center;
                        background: var(--pagination-number);
                        border-radius: 50%;
                        transition: background .3s ease;
                    }
                    &:hover {
                        a {
                            background: var(--pagination-number-hover);
                        }
                    }
                    &.active {
                        a {
                            background: var(--pagination-number-active);
                        }
                    }
                }
                &:first-child,
                &:last-child {
                    a {
                        padding: 4px;
                        svg {
                            color: var(--text);
                            display: block;
                            width: 16px;
                            height: 16px;
                            opacity: .4;
                            transition: color .3s ease, opacity .3s ease;
                        }
                        &:hover {
                            svg {
                                opacity: 1;
                            }
                        }
                    }
                    &.disabled {
                        a {
                            cursor: not-allowed;
                            svg {
                                color: var(--text-muted);
                                opacity: .4;
                            }
                        }
                    }
                }
            }
        }
    }
}
</style>
