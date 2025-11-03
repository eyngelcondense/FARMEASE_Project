# Docker Instruction for Event System (One-Command Workflow)

## 1. Install Docker
1. Download Docker Desktop for your operating system: https://www.docker.com/get-started
2. Follow the installation instructions for your OS.
3. Verify installation by running in terminal:
   ```bash
   docker --version
   docker compose version
   ```

## 2. One-Command VSCode Terminal Setup
1. Open VSCode and open your terminal (**Terminal → New Terminal**).
2. Navigate to your `event-system` project directory:
   ```bash
   cd path/to/event-system
   ```
3. Run the following command to build, start containers, enter the app container, and run migrations in one go:
   ```bash
   docker compose up --build -d && \
   docker exec -it farmease-app bash -c "php spark migrate"
   ```

## 3. Verify
1. Check that your containers are running:
   ```bash
   docker ps
   ```
2. Open your browser and go to your app URL (e.g., http://localhost:8080) to ensure everything is running.

