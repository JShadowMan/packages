#include <iostream>

int main(void) {
    int apple = 9;

    auto lambda_capture_value = [apple] () { return apple; };
    auto lambda_capture_reference = [&apple] () { return apple; };
    auto lambda_auto_capture_value = [=] () { return apple; };
    auto lambda_auto_capture_reference = [&] () { return apple; };
    apple = 0;

    std::cout << "apple = " << apple
        << ", lambda_capture_value return value = " << lambda_capture_value()
        << ", lambda_capture_reference return value = " << lambda_capture_reference()
        << ", lambda_auto_capture_value return value = " << lambda_auto_capture_value()
        << ", lambda_auto_capture_reference return value = " << lambda_auto_capture_reference()
        << std::endl;

    return 0;
}
