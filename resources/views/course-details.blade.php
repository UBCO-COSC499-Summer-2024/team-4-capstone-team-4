<x-app-layout>
    <div class="content">
        <h1>{{ __('Department Details') }}</h1>
        <section class="dash-bottom">
            <br><br>
          <a href="#">Earnest Bell</a>
          <br>
          <a href="#">Andrea Christiansen</a>
          <br>
          <a href="#">Naomi Evans</a>
          <br>
          <a href="#">Xavier Oquendo</a>
        </section>
        <section class="coursedetails-table">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Duration</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled Students</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dropped Students</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Capacity</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="course-details-table-body">
                    <!-- Javascript-->
                </tbody>
            </table>
        </section>
    </div>
</x-app-layout>