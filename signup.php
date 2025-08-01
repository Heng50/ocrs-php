<?php
    require_once 'header.php';
    session_start();
?>
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 rounded-2xl shadow-xl border border-black/10">
            <?php if(isset($_GET['success']) && $_GET['success'] == '0' && isset($_GET['message'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php elseif(isset($_GET['success']) && $_GET['success'] == '1' && isset($_GET['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($_GET['message']); ?>
                </div>
            <?php elseif(isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['error']; ?>
                </div>
            <?php elseif(isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['success']; ?>
                </div>
            <?php endif; ?>
            
            <h1 class="text-3xl font-bold text-black mb-6 text-center">Create Account</h1>
            
            <form action="backend/auth.php" method="post" class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-black mb-1">Username</label>
                    <input 
                        type="text" 
                        id="username"
                        name="username" 
                        placeholder="Enter your username"
                        class="mt-1 block w-full rounded-md border border-black px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700]"
                        required
                    >
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-black mb-1">Email</label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        placeholder="Enter your email"
                        class="mt-1 block w-full rounded-md border border-black px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700]"
                        required
                    >
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-black mb-1">Password</label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        placeholder="Enter your password"
                        class="mt-1 block w-full rounded-md border border-black px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700]"
                        required
                    >
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-black mb-1">Confirm Password</label>
                    <input 
                        type="password" 
                        id="confirm_password"
                        name="confirm_password" 
                        placeholder="Confirm your password"
                        class="mt-1 block w-full rounded-md border border-black px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#FFD700] focus:border-[#FFD700]"
                        required
                    >
                </div>
                
                <button 
                    type="submit" 
                    name="action" 
                    value="signup"
                    class="w-full bg-[#FFD700] text-black font-semibold py-2 rounded-md hover:bg-[#e6c200] transition"
                >
                    Sign Up
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-black">
                    Already have an account? 
                    <a href="index.php" class="text-[#FFD700] hover:text-[#e6c200] font-medium transition">
                        Login
                    </a>
                </p>
            </div>
        </div>
    </div>

<?php
    require_once 'footer.php';
?>