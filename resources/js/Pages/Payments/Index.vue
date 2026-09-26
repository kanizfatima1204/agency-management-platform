<script setup>
import AppLayout from '../../Layouts/AppLayout.vue';

defineProps({ payments: Array, stats: Object, isAdmin: Boolean });
</script>

<template>
  <AppLayout>
    <template #title>{{ isAdmin ? 'Finance overview' : 'Project billing' }}</template>
    <div class="welcome-banner finance-banner"><div><span class="eyebrow">{{ isAdmin ? 'AGENCY FINANCE' : 'YOUR PROJECT FINANCE' }}</span><h2>{{ isAdmin ? 'A clear view of project payments.' : 'Your project payment records.' }}</h2><p>{{ isAdmin ? 'Review what has been collected and what is still outstanding.' : 'See payment status and references for your projects.' }}</p></div><span class="welcome-mark">＄</span></div>
    <div class="stats finance-stats"><article><small>PAID RECORDS</small><b>{{ stats.paid }}</b></article><article><small>PENDING RECORDS</small><b>{{ stats.pending }}</b></article><article class="dark"><small>OVERDUE RECORDS</small><b>{{ stats.overdue }}</b></article></div>
    <div class="panel"><div class="panel-heading"><div><small>PAYMENT RECORDS</small><h2>{{ isAdmin ? 'All agency payments' : 'Payments by project' }}</h2></div><span class="count-pill">{{ payments.length }} records</span></div>
      <div v-for="payment in payments" :key="payment.id" class="payment-row"><div class="payment-mark" :class="`payment-${payment.status}`">{{ payment.status === 'paid' ? '✓' : payment.status === 'overdue' ? '!' : '↗' }}</div><div class="payment-info"><b>{{ payment.project?.name }}</b><small>{{ payment.project?.client?.name }}<span v-if="payment.reference"> · Ref {{ payment.reference }}</span></small></div><div class="payment-amount"><b>{{ payment.currency }} {{ Number(payment.amount).toLocaleString() }}</b><small>{{ payment.due_date ? `Due ${payment.due_date}` : 'No due date' }}</small></div><mark :class="`badge-${payment.status}`">{{ payment.status }}</mark><Link :href="`/projects/${payment.project_id}`" class="payment-open">Open <span>↗</span></Link></div>
      <div v-if="!payments.length" class="empty-state"><span>＄</span><b>No payment records yet.</b><p>When payments are added to your projects, they will appear here.</p></div>
    </div>
  </AppLayout>
</template>
