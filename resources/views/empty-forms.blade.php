<div class="flex items-center">
    <div class="container flex flex-col md:flex-row items-center justify-center px-5">
        <div class="max-w-md">
            <div class="text-5xl font-dark font-bold">It's empty here :(</div>
            <p class="text-2xl md:text-3xl font-light leading-normal">Sorry we couldn't find any forms. </p>
            <p class="mb-8">But don't worry, you can easily add one, by folowing this steps.</p>
        </div>
  </div>
</div>
<div class="relative wrap overflow-hidden p-10 h-full">
    <div class="border-2-2 absolute border-opacity-20 border-gray-700 h-full border" style="left: 50%"></div>
    <div class="mb-8 flex justify-between items-center w-full right-timeline">
        <div class="order-1 w-5/12"></div>
        <div class="z-20 flex items-center order-1 bg-formsydark shadow-xl w-8 h-8 rounded-full">
            <h1 class="mx-auto font-semibold text-lg text-white">1</h1>
        </div>
        <div class="order-1 bg-formsypurple text-white rounded-lg shadow-xl w-5/12 px-6 py-4">
            <h3 class="mb-3 font-bold text-xl">Create an HTML Form</h3>
            <p class="text-sm leading-snug tracking-wide text-opacity-100">Create or edit an existing form that you want to connect to {{ env('APP_NAME') }}.</p>
        </div>
    </div>
    <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
        <div class="order-1 w-5/12"></div>
        <div class="z-20 flex items-center order-1 bg-formsydark shadow-xl w-8 h-8 rounded-full">
            <h1 class="mx-auto text-white font-semibold text-lg">2</h1>
        </div>
        <div class="order-1 bg-formsydark rounded-lg shadow-xl w-5/12 px-6 py-4">
            <h3 class="mb-3 font-bold text-white text-xl">Connect the form</h3>
            <p class="text-sm font-medium leading-snug tracking-wide text-white text-opacity-100">In order to start receiving submissions you need to connect the new form with {{ env('APP_NAME') }} by altering the action attribute.</p>
        </div>
    </div>
    <div class="mb-8 flex justify-between items-center w-full right-timeline">
        <div class="order-1 w-5/12"></div>
        <div class="z-20 flex items-center order-1 bg-formsydark shadow-xl w-8 h-8 rounded-full">
            <h1 class="mx-auto font-semibold text-lg text-white">3</h1>
        </div>
        <div class="order-1 bg-formsypurple text-white rounded-lg shadow-xl w-5/12 px-6 py-4">
            <h3 class="mb-3 font-bold  text-xl">See submissions</h3>
            <p class="text-sm leading-snug tracking-wide text-opacity-100">Submit the new form. If you followed the previus steps then after a first submission is made, you should see your form on the list.</p>
        </div>
    </div>
  </div>
