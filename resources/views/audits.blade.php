<x-app-layout>
    <div class="content">
      <h1>{{ __('Audit Logs') }}</h1>

      <section>
            <livewire:audit-log-table :viewMode="$viewMode" :perpage="$perpage" :page="$page" />
      </section>
    </div>
</x-app-layout>
