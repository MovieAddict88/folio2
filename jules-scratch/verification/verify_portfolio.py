from playwright.sync_api import sync_playwright, expect

def run(playwright):
    browser = playwright.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()

    try:
        # Verify public portfolio page
        print("Navigating to the main portfolio page...")
        page.goto("http://localhost:5173/")
        # Wait for a key element to be visible
        expect(page.get_by_role("heading", name="My Portfolio")).to_be_visible()
        page.screenshot(path="jules-scratch/verification/01_portfolio_homepage.png")
        print("Screenshot of the homepage taken.")

        # Verify admin login page
        print("Navigating to the admin login page...")
        page.goto("http://localhost:5173/admin/login")
        expect(page.get_by_role("heading", name="Admin Login")).to_be_visible()
        page.screenshot(path="jules-scratch/verification/02_admin_login_page.png")
        print("Screenshot of the admin login page taken.")

        # Test login functionality (using placeholder credentials)
        print("Attempting to log in...")
        page.get_by_label("Username").fill("admin")
        page.get_by_label("Password").fill("password")
        page.get_by_role("button", name="Login").click()

        # Check for successful login by looking for the dashboard header
        # or for an error message.
        try:
            dashboard_header = page.get_by_role("heading", name="Admin Dashboard")
            expect(dashboard_header).to_be_visible()
            print("Login successful, taking dashboard screenshot.")
            page.screenshot(path="jules-scratch/verification/03_admin_dashboard.png")
        except Exception:
            error_message = page.locator(".error-message")
            expect(error_message).to_be_visible()
            print("Login failed as expected, taking error screenshot.")
            page.screenshot(path="jules-scratch/verification/03_admin_login_failed.png")

    except Exception as e:
        print(f"An error occurred: {e}")
        page.screenshot(path="jules-scratch/verification/error.png")

    finally:
        browser.close()

with sync_playwright() as playwright:
    run(playwright)